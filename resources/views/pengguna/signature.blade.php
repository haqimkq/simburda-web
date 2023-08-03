@extends('layouts.signature')
@push('prepend-style')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
	<style>
		*,
		*::before,
		*::after {
			box-sizing: border-box;
		}

		body {
			display: -webkit-box;
			display: -ms-flexbox;
			display: flex;
			-webkit-box-pack: center;
					-ms-flex-pack: center;
							justify-content: center;
			-webkit-box-align: center;
					-ms-flex-align: center;
							align-items: center;
			height: 100vh;
			width: 100%;
			-webkit-user-select: none;
				-moz-user-select: none;
					-ms-user-select: none;
							user-select: none;
			margin: 0;
			padding: 32px 16px;
			font-family: Helvetica, Sans-Serif;
		}
		.signature-pad {
			position: relative;
			display: -webkit-box;
			display: -ms-flexbox;
			display: flex;
			-webkit-box-orient: vertical;
			-webkit-box-direction: normal;
					-ms-flex-direction: column;
							flex-direction: column;
			font-size: 10px;
			width: 100%;
			height: 100%;
			max-width: 350px;
			max-height: 460px;
			border: 1px solid #e8e8e8;
			background-color: #fff;
			box-shadow: 0 1px 4px rgba(0, 0, 0, 0.102), 0 0 40px rgba(0, 0, 0, 0.08) inset;
			border-radius: 4px;
			padding: 16px;
		}

		.signature-pad::before,
		.signature-pad::after {
			position: absolute;
			z-index: -1;
			content: "";
			width: 40%;
			height: 10px;
			bottom: 10px;
			background: transparent;
		}

		.signature-pad--body {
			position: relative;
			-webkit-box-flex: 1;
					-ms-flex: 1;
							flex: 1;
			border: 1px solid #f4f4f4;
		}

		.signature-pad--body
		canvas {
			position: relative;
			left: 0;
			top: 0;
			width: 100%;
			height: 20em;
			border-radius: 4px;
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.02) inset;
		}

		.signature-pad--footer {
			color: #C3C3C3;
			text-align: center;
			font-size: 1.2em;
			margin-top: 8px;
		}

		.signature-pad--actions {
			display: -webkit-box;
			display: -ms-flexbox;
			display: flex;
			-webkit-box-pack: justify;
					-ms-flex-pack: justify;
							justify-content: space-between;
			margin-top: 8px;
		}
	</style>
@endpush
@section('content')
  <div id="signature-pad" class="signature-pad">
    <div class="signature-pad--body">
      <canvas></canvas>
    </div>
    <div class="signature-pad--footer">
      <div class="signature-pad--actions">
        <div>
          <button type="button" class="button" data-action="clear">Clear</button>
          <button type="button" class="button" data-action="undo">Undo</button>
        </div>
        <div>
          <button type="button" class="button save" data-action="save-png">Save as PNG</button>
          <button type="button" class="button upload" data-action="upload">Upload</button>
					<form action="{{route('signature.store')}}" method="POST" id="uploadSignatureForm">
						@csrf
						<textarea id="signatureValue" name="signature" style="display: none"></textarea>
					</form>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('addon-script')
	@include('includes.jquery')
	<script type="text/javascript">
		var wrapper = document.getElementById("signature-pad");
		var clearButton = wrapper.querySelector("[data-action=clear]");
		var uploadButton = wrapper.querySelector("[data-action=upload]");
		var undoButton = wrapper.querySelector("[data-action=undo]");
		// var saveJPGButton = wrapper.querySelector("[data-action=save-jpg]");
		var savePNGButton = wrapper.querySelector("[data-action=save-png]");
		var canvas = wrapper.querySelector("canvas");
		var signaturePad = new SignaturePad(canvas);

		// Adjust canvas coordinate space taking into account pixel ratio,
		// to make it look crisp on mobile devices.
		// This also causes canvas to be cleared.
		function resizeCanvas() {
			// When zoomed out to less than 100%, for some very strange reason,
			// some browsers report devicePixelRatio as less than 1
			// and only part of the canvas is cleared then.
			var ratio =  Math.max(window.devicePixelRatio || 1, 1);

			// This part causes the canvas to be cleared
			canvas.width = canvas.offsetWidth * ratio;
			canvas.height = canvas.offsetHeight * ratio;
			canvas.getContext("2d").scale(ratio, ratio);

			// This library does not listen for canvas changes, so after the canvas is automatically
			// cleared by the browser, SignaturePad#isEmpty might still return false, even though the
			// canvas looks empty, because the internal data of this library wasn't cleared. To make sure
			// that the state of this library is consistent with visual state of the canvas, you
			// have to clear it manually.
			signaturePad.clear();
		}

		// On mobile devices it might make more sense to listen to orientation change,
		// rather than window resize events.
		window.onresize = resizeCanvas;
		resizeCanvas();

		function download(dataURL, filename) {
			var blob = dataURLToBlob(dataURL);
			var url = window.URL.createObjectURL(blob);

			var a = document.createElement("a");
			a.style = "display: none";
			a.href = url;
			a.download = filename;

			document.body.appendChild(a);
			a.click();

			window.URL.revokeObjectURL(url);
		}

		// One could simply use Canvas#toBlob method instead, but it's just to show
		// that it can be done using result of SignaturePad#toDataURL.
		function dataURLToBlob(dataURL) {
			// Code taken from https://github.com/ebidel/filer.js
			var parts = dataURL.split(';base64,');
			var contentType = parts[0].split(":")[1];
			var raw = window.atob(parts[1]);
			var rawLength = raw.length;
			var uInt8Array = new Uint8Array(rawLength);

			for (var i = 0; i < rawLength; ++i) {
				uInt8Array[i] = raw.charCodeAt(i);
			}

			return new Blob([uInt8Array], { type: contentType });
		}

		clearButton.addEventListener("click", function (event) {
			signaturePad.clear();
		});

		uploadButton.addEventListener("click", function (event) {
			var data = signaturePad.toDataURL();
			$('#signatureValue').val(data);
			$('#uploadSignatureForm').submit();
		});

		undoButton.addEventListener("click", function (event) {
			var data = signaturePad.toData();
			if (data) {
				data.pop(); // remove the last dot or line
				signaturePad.fromData(data);
			}
		});

		// saveJPGButton.addEventListener("click", function (event) {
		// 	if (signaturePad.isEmpty()) {
		// 		alert("Please provide a signature first.");
		// 	} else {
		// 		var dataURL = signaturePad.toDataURL("image/jpeg");
		// 		download(dataURL, "TTD - {{$userAuth->nama}}.jpg");
		// 	}
		// });
		savePNGButton.addEventListener("click", function (event) {
			if (signaturePad.isEmpty()) {
				alert("Please provide a signature first.");
			} else {
				var dataURL = signaturePad.toDataURL();
				// download(dataURL, "TTD - {{$userAuth->nama}}.png");
				download(dataURL, "TTD - {{$randomId}}.png");
			}
		});
	</script>
@endpush

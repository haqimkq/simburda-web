@extends('layouts.email')

@section('body')
    <p>You recently requested to reset your password for your Simburda account. Use the button below to reset it. <strong>This password reset is only valid for the next 24 hours.</strong></p>
    <!-- Action -->
    <table class="body-action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
      <tr>
        <td align="center">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" role="presentation">
            <tr>
              <td align="center">
                <a href="{{ route('reset.password.get', ['token' => $token, 'email' => $email ])  }}" class="f-fallback button button--green" target="_blank">Reset your password</a>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <p> If you did not request a password reset, please ignore this email or contact support if you have questions.</p>
    <p>Thanks,
      <br>The Simburda team</p>
@endsection
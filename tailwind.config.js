/** @type {import('tailwindcss').Config} */
module.exports = {
    important: true,
    content: [
        "./storage/framework/views/*.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js",
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    ],
    theme: {
        fontFamily: {
            sans: ['"Poppins"', "sans-serif"],
        },
        colors: {
            black: "#333333",
            primary: {
                DEFAULT: "#1F4844",
                70: "#607C7A",
                50: "#899E9C",
                30: "#B5C2C0",
                light: "#3F6F6A",
            },
            green: {
                DEFAULT: "#8EC03D",
                light: "#DDF1BC",
            },
            red: "#C04242",
            orange: "#EC9E42",
        },
    },
    plugins: [require("flowbite/plugin"), require("@tailwindcss/line-clamp")],
};

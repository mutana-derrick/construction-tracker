/** @type {import('tailwindcss').Config} */
export default {
    content: ["./resources/views/**/*.blade.php", "./resources/js/**/*.js"],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: "#fffef2",
                    100: "#fffde6",
                    200: "#fffac3",
                    300: "#fff69a",
                    400: "#EAF06A",
                    500: "#d4d84f",
                    600: "#b8bc34",
                    700: "#9ca02a",
                    800: "#808420",
                    900: "#646816",
                },
            },
        },
    },
    plugins: [],
};

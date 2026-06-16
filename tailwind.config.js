import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            colors: {
                navy: "#0C1E35",
                ocean: "#133558",
                sea: "#1A6896",
                tide: "#2A8DC4",
                brass: "#C4893A",
                gold: "#DBA94E",
                sand: "#F6F1E8",
                foam: "#EEF5FA",
                ink: "#0A1828",
                mist: "#7A8FA0",
            },
            fontFamily: {
                display: ["Cormorant Garamond", "Georgia", "serif"],
                body: ["Inter", "system-ui", "sans-serif"],
                mono: ["JetBrains Mono", ...defaultTheme.fontFamily.mono],
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};

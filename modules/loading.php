<style>
    .rotate {
        display: inline-block;
        /* Ensures that the label behaves like an inline element */
        animation: spin 1s infinite ease-in-out;
        transform-origin: center;
        /* Ensures the rotation happens around the center */
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
<label class="m-0 p-0 bi bi-arrow-clockwise rotate"></label>
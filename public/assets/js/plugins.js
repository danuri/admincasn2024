// (document.querySelectorAll("[toast-list]")||document.querySelectorAll("[data-choices]")||document.querySelectorAll("[data-provider]"))&&(document.writeln("<script type='text/javascript' src='https://cdn.jsdelivr.net/npm/toastify-js'><\/script>"),document.writeln("<script type='text/javascript' src='"+base_url+"/assets/libs/choices.js/public/assets/scripts/choices.min.js'><\/script>"),document.writeln("<script type='text/javascript' src='"+base_url+"/assets/libs/flatpickr/flatpickr.min.js'><\/script>"));
if (
    document.querySelectorAll("[toast-list]").length ||
    document.querySelectorAll("[data-choices]").length ||
    document.querySelectorAll("[data-provider]").length
) {
    const loadScript = (src) => {
        const script = document.createElement("script");
        script.type = "text/javascript";
        script.src = src;
        document.head.appendChild(script);
    };

    // Toastify
    loadScript("https://cdn.jsdelivr.net/npm/toastify-js");

    // Choices.js
    loadScript(base_url + "/assets/libs/choices.js/public/assets/scripts/choices.min.js");

    // Flatpickr
    loadScript(base_url + "/assets/libs/flatpickr/flatpickr.min.js");
}

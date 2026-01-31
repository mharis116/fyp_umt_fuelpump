<script>
    function toggleModuleCheckboxes(module_id){
        let isChecked = $(`#all_checkbox_module_${module_id}`).prop("checked");
        $(`.checkbox_module_${module_id}`).prop('checked', isChecked?true:false)
    }
    $(document).ready(function(){
        $('[data-bs-toggle="collapse"]').each(function () {
            const $button = $(this);
            const $icon = $button.find('.arrow-icon');
            const $text = $button.find('.expand-text');
            const targetSelector = $button.data('bs-target');
            const $target = $(targetSelector);

            $target.on('shown.bs.collapse', function () {
                $icon.removeClass('bi-chevron-left').addClass('bi-chevron-down');
                // $text.text('Collapse');
            });

            $target.on('hidden.bs.collapse', function () {
                $icon.removeClass('bi-chevron-down').addClass('bi-chevron-left');
                // $text.text('Expand');
            });
        });

        // Permission logic for "read" rule
        $(".permission-checkbox").on("change", function () {
            let $this = $(this);
            let moduleId = $this.data("module-id");   // ✅ Safe and direct
            let code = $this.data("code");

            // Rule B: If "read" unchecked, uncheck all in same module
            if (code === "read" && !$this.is(":checked")) {
                $(`.checkbox_module_${moduleId}`).prop("checked", false);
            }

            // Rule A: If another permission is checked, ensure "read" is also checked
            if (code !== "read" && $this.is(":checked")) {
                $(`.checkbox_module_${moduleId}[data-code="read"]`).prop("checked", true);
            }
        });
    });
</script>
<style>
    .permission-checkbox{
        vertical-align: middle;
        margin-bottom: 3px;
    }
    /* .accordion-button:not(.collapsed)::after{

    } */
</style>

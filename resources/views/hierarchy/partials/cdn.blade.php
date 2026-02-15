<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jstree@3.3.15/dist/themes/default/style.min.css" />
<script src="https://cdn.jsdelivr.net/npm/jstree@3.3.15/dist/jstree.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Define icon per hierarchy level

        $('#hierarchy-tree').jstree({
            core: {
                data: {
                    url: "{{ route('hierarchy.tree') }}",
                    dataType: 'json'
                },
                themes: {
                    dots: true,
                    icons: true
                }
            },
            plugins: [
                // 'contextmenu',
                // 'wholerow',
                'types',
                'state'
            ],
            types: {
                default: { icon: 'default' }
            },
        });

        // Display node details on selection
        $('#hierarchy-tree').on('select_node.jstree', function(e, data) {
            const nodeData = data.node.data;
            // console.log(nodeData);

            const info = `
                <div class="mt-3 p-3 border rounded bg-light" id='node_data' data-node='${JSON.stringify(nodeData)}'>
                    ${nodeData.hierarchy_level_id < nodeData.last_levels[0]?.id ? `<div class="btn btn-outline-primary btn-sm float-right" onclick='addNode("node_data", "child")'>Add Child Location</div>` :''}
                    <div class="btn btn-outline-primary btn-sm float-right me-2" onclick='addNode("node_data", "brother")'>Add Brother Location</div>
                    <div class="btn btn-outline-info btn-sm" onclick='addNode("node_data", "edit", ${JSON.stringify(nodeData)})'>Edit Location</div><br><br>
                    <strong>Level:</strong> ${nodeData.level || '-'} <br>
                    <strong>Name:</strong> ${nodeData.name || '-'} <br>
                    <strong>Code:</strong> ${nodeData.code || '-'} <br>
                    <strong>Address:</strong> ${nodeData.address || '-'}
                </div>
            `;

            // Remove existing detail div if already exists
            $('#node-details').remove();

            // Append details below tree
            $('#tree-wrapper').append(`<div id="node-details">${info}</div>`);
        });
    });
    // Expand all nodes
    $('#expand-all').on('click', function() {
        $('#hierarchy-tree').jstree('open_all');
    });

    // Collapse all nodes
    $('#collapse-all').on('click', function() {
        $('#hierarchy-tree').jstree('close_all');
    });

    function addNode(id, type='child', node = {}){
        node_data = $(`#${id}`).data('node');
        $('#node_form #node_type').val(type);
        // node_data = JSON.parse(node_data);
        // console.log("node_data", node_data);

        if(type == 'child'){
            $('#node_form #node_hierarchy_level_id').val(node_data.hierarchy_next_level_id);
            $('#node_form #node_parent_id').val(node_data.hierarchy_id);
        }else if(type == 'brother'){
            $('#node_form #node_hierarchy_level_id').val(node_data.hierarchy_level_id);
            $('#node_form #node_parent_id').val(node_data.hierarchy_parent_id);
        }

        if((node_data.last_levels[1]?.id != node_data.hierarchy_level_id && type == 'child') || (node_data.hierarchy_level_id != node_data.last_levels[0]?.id && type == 'brother')){
            $('#node_form #node_code_wrapper').hide();
            $('#node_form #node_address_wrapper').hide();

            $('#node_form #node_code').val('');
            $('#node_form #node_address').val('');
        }else{
            $('#node_form #node_code_wrapper').show();
            $('#node_form #node_address_wrapper').show();
        }

        if(type == "edit"){
            $('#node_form #node_name').val(node_data.name);
            $('#node_form #node_id').val(node_data.hierarchy_id);
            $('#node_form #node_location_id').val(node_data.location_id);

            $('#node_form #node_hierarchy_level_id').val(node_data.hierarchy_level_id);
            $('#node_form #node_parent_id').val(node_data.hierarchy_parent_id);

            if(node_data.hierarchy_level_id == node_data.last_levels[0]?.id){
                $('#node_form #node_code').val(node_data.code);
                $('#node_form #node_address').val(node_data.address);
                $('#node_form #node_code_wrapper').show();
                $('#node_form #node_address_wrapper').show();
            }else{
                $('#node_form #node_code_wrapper').hide();
                $('#node_form #node_address_wrapper').hide();
            }

        }else{
            $('#node_form #node_name').val("");
            $('#node_form #node_code').val("");
            $('#node_form #node_address').val("");
        }

        $('#addNodeModal').modal('show');

    }

    $('#node_form').on('submit', function(e){
        e.preventDefault();

        let form = $(this);
        let formData = form.serialize(); // serialize form fields
        let submitBtn = form.find('button[type="submit"]');

        submitBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split me-1"></i> Please wait...');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            success: function(res){
                // show success toast
                toastr.success("Location added / updated successfully!");

                // hide modal
                $('#addNodeModal').modal('hide');

                // refresh tree
                refreshTree(res.new_node_id); // pass the new node ID returned from server
            },
            error: function(err){
                if(err.responseJSON?.message){
                    toastr.error(err.responseJSON?.message);
                }
                err.responseJSON?.errors?.forEach(function(error){
                    toastr.error(error);
                })
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i> Submit');
            }
        });
    });

    function getOpenedNodes(tree){
        // Get all nodes in tree
        let allNodes = tree.get_json('#', { flat: true });
        // Filter nodes that are opened
        return allNodes.filter(n => tree.is_open(n.id)).map(n => n.id);
    }


    function refreshTree(newNodeId = null){
        let tree = $('#hierarchy-tree').jstree(true);
        if(!tree) return;

        // save opened nodes
        // let openedNodes = getOpenedNodes(tree);

        // refresh tree
        tree.refresh();

        // tree.one('refresh.jstree', function(){
        //     // reopen previous nodes
        //     openedNodes.forEach(function(id){
        //         if(tree.get_node(id)) tree.open_node(id);
        //     });

        //     // focus new node
        //     if(newNodeId && tree.get_node(newNodeId)){
        //         tree.open_node(newNodeId, function(){
        //             tree.deselect_all();
        //             tree.select_node(newNodeId);
        //             let el = tree.get_node(newNodeId, true)[0];
        //             if(el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        //         });
        //     }
        // });
    }

</script>
<style>
    /* 🌙 Dark Theme Overrides */
    /* #hierarchy-container {
        border: 1px solid #333;
        color: #ddd;
    }

    .jstree-default .jstree-anchor {
        color: #ddd !important;
    }
    .jstree-default .jstree-anchor:hover {
        color: #fff !important;
        background-color: #2d2d2d !important;
    }
    .jstree-default .jstree-clicked {
        background-color: #3a3a3a !important;
        color: #fff !important;
    }
    .jstree-default .jstree-icon {
        filter: brightness(0.8);
    }
    .jstree-default .jstree-wholerow-hovered,
    .jstree-default .jstree-hovered {
        background-color: #3a3a3a !important;
    }
    .jstree-default .jstree-node {
        border-left: 1px dashed #444;
    }
    #node-details {
        border-color: #444 !important;
    } */
    .jstree-default .jstree-themeicon.default::before {
        content: "🌍";
        font-style: normal;
    }

    #hierarchy-tree{
        max-height:300px;
        overflow-y:auto;
    }
</style>

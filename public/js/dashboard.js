document.addEventListener('DOMContentLoaded', function () {
    // Initialize DataTables
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('.datatable').DataTable({
            paging: true,
            searching: true,
            info: true,
            responsive: true,
            language: {
                search: "Search:",
                searchPlaceholder: "Type to search...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries"
            },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>><"row"<"col-sm-12"tr>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            initComplete: function () {
                // Wrap the search input in a Bootstrap input group
                $('.dataTables_filter').each(function() {
                    var $search = $(this).find('input[type="search"]');
                    var html = `
                        <div class="input-group">
                            ${$search[0].outerHTML}
                            <button class="btn btn-primary" type="button" id="searchBtn">
                                <i class="bi bi-search"></i>
                            </button>
                            <button class="btn btn-outline-secondary" type="button" id="clearBtn">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </div>
                    `;
                    
                    $(this).html(html);
                    
                    // Get the new input
                    var $newInput = $(this).find('input[type="search"]');
                    var api = this.api();
                    
                    // Search button click
                    $(this).find('#searchBtn').on('click', function() {
                        api.search($newInput.val()).draw();
                    });
                    
                    // Clear button click
                    $(this).find('#clearBtn').on('click', function() {
                        $newInput.val('');
                        api.search('').draw();
                    });
                    
                    // Search on Enter
                    $newInput.on('keyup', function(e) {
                        if (e.keyCode === 13) {
                            api.search(this.value).draw();
                        }
                    });
                });
            }
        });
    }
});
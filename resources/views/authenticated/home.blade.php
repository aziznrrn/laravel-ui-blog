@extends('layouts.app')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold active" id="pills-article-tab"
                            data-bs-toggle="pill" data-bs-target="#pills-article"
                            type="button" role="tab" aria-controls="pills-article"
                            aria-selected="true" title="tab article">Article</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" id="pills-category-tab"
                            data-bs-toggle="pill" data-bs-target="#pills-category"
                            type="button" role="tab" aria-controls="pills-category"
                            aria-selected="false" title="tab category">Category</button>
                </li>
            </ul>
            <hr>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-article"
                     role="tabpanel" aria-labelledby="pills-article-tab">
                    <table class="table table-bordered" id="article-table">
                        <thead class="bg-teal text-white">
                            <tr>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Category</th>
                                <th>Author</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="pills-category"
                     role="tabpanel" aria-labelledby="pills-category-tab">
                     <table class="table table-bordered" id="category-table">
                        <thead class="bg-teal text-white">
                            <tr>
                                <th>Name</th>
                                <th>Author</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Article -->
    <div class="modal fade" id="article-modal" tabindex="-1" aria-labelledby="article-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-gradient-violet fw-bold" id="article-modalLabel">Article</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form action="{{ route('articles.store') }}" method="post"
                      class="row" id="article-form">
                    @csrf
                    @method('POST')
                    <input type="hidden" id="article-id">
                    <div class="col-md-9 mb-3">
                        <label for="article-title">Title</label>
                        <input type="text" class="form-control" id="article-title" name="title" placeholder="Enter title">
                    </div>
                    <div class="mb-3 col-md-3">
                        <label for="article-category">Category</label>
                        <select class="form-control" id="article-category" name="category_id">
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="article-content">Content</label>
                        <textarea class="form-control" id="article-content" name="content" rows="3"></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <span>
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </span>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Category -->
    <div class="modal fade" id="category-modal" tabindex="-1" aria-labelledby="category-modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-gradient-violet fw-bold" id="category-modalLabel">Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form action="{{ route('categories.store') }}" method="post" id="category-form">
                    @csrf
                    @method('POST')
                    <input type="hidden" id="category-id">
                    <div class="mb-3">
                        <label for="category-name">Name</label>
                        <input type="text" class="form-control" id="category-name" name="name" placeholder="Enter category name">
                    </div>
                    <div class="d-flex justify-content-end">
                        <span>
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </span>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        /**
         * Generic utility functions
         */ 
         const submitData = (r, table, modal, form, id) => {
            $.ajax({
                type: "POST",
                data: $(form).serialize(),
                url: route(r, id),
                success: function (data) {
                    modal.modal('hide');
                    (table)()
                    swalSuccess(data.message)
                }
            });
        }

        const deleteData = (r, table, modal, form, id) => {
            Swal.fire({
                title: 'Are you sure?',
                text: "After this delete operation, data cannot be returned",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete',
                confirmButtonColor: '#20c997',
            }).then((response) => {
                if (response.isConfirmed) {
                    submitData(r, table, modal, form, id);
                } else if (r.dismiss === Swal.DismissReason.cancel) {
                    swalWarning('Delete operation canceled, your data is safe');
                }
            })
            .catch( function (e) {
                swalError(e.message);
            })
        }

        const resetForm = (form) => {
            $(form)[0].reset();
        }

        /**
         * Article
         */
        const articleTable = $('#article-table');
        const articleModal = $('#article-modal');
        const articleForm = $('#article-form');
        const articleTab = document.querySelector('#pills-article-tab');
        articleTab.addEventListener('shown.bs.tab', function (event) {
            articleDatatables()
        })

        const articleDatatables = () => {
            generateDatatable({
                table: articleTable,
                ajax: "{{ route('articles.index') }}",
                columns: [
                    {
                        data: 'title',
                        name: 'title',
                        width: '17%', 
                    },
                    {
                        data: 'content',
                        name: 'content',
                        width: '35%'
                    },
                    {
                        data: 'category.name',
                        name: 'category.name',
                        width: '12%'
                    },
                    {
                        data: 'user.name',
                        name: 'user.name',
                        width: '12%'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: '12%',
                        render: function (data) {
                            return formatDate(data, 'Y/m/d H:i');
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        width: '12%',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [
                    { targets: [0, -4, -3, -2, -1],className: 'text-center' },
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 2, targets: -1 },
                    { responsivePriority: 3, targets: -3 },
                    { responsivePriority: 4, targets: -4 },
                ],
                initComplete: function (setting, json) {
                    $('#article-table_length').html(`
                        <button class="btn btn-sm bg-gradient-violet text-white"
                                id="article-add-btn"
                                title="Create Article">
                            <i class="fa-regular fa-square-plus fw-bold"></i>
                        </button>
                    `);

                    $('#article-table_wrapper')
                        .children(':first-child')
                        .addClass('d-flex justify-content-between mt-4 mb-1')
                        .removeClass('row');
                    
                    $('#article-table_wrapper')
                        .children(':first-child')
                        .children()
                        .removeClass('col-sm-12 col-md-6');

                    $('#article-add-btn').on('click', function () {
                        articleAdd();
                    });        
                }
            })
        }
        articleDatatables()

        const getCategory = (id) => {
            $.ajax({
                type: "GET",
                url: route('categories.list'),
                success: function (categories) {
                    $('#article-category').html('');
                    $('#article-category').append(`<option value="">Select Category</option>`);
                    $.each(categories, function (key, value) {
                        $('#article-category').append(`<option value="${key}">${value}</option>`);
                    });
                    if (id) {
                        $('#article-category').find(`option[value=${id}]`).attr('selected', true);
                    }
                }
            });
        }

        const articleAdd = () => {
            getCategory();
            articleForm.find('[name="_method"]').val('POST');
            articleModal.modal('show');
        }

        const articleEdit = (id) => {
            $.ajax({
                type: "GET",
                url: route('articles.edit', id),
                success: function (data) {
                    getCategory(data.category_id);
                    articleForm.find('[name="_method"]').val('PUT');
                    articleForm.find('#article-id').val(data.id);
                    articleForm.find('#article-title').val(data.title);
                    articleForm.find('#article-content').val(data.content);
                    articleModal.modal('show');
                }
            });
        }

        const articleSubmit = () => {
            let id = articleForm.find('#article-id').val();
            let r = (id) ? 'articles.update' : 'articles.store';
            let table = articleDatatables;
            let modal = articleModal;
            let form = articleForm;
            submitData(r, table, modal, form, id);
        }

        const articleDelete = (id) => {
            let table = articleDatatables;
            articleForm.find('[name="_method"]').val('DELETE');
            deleteData('articles.destroy', table, articleModal, articleForm, id);
        }

        $(function () {
            articleTable.on('click', '.btn-edit', function () {
                articleEdit(this.dataset.id);
            });
    
            articleTable.on('click', '.btn-delete', function () {
                articleDelete(this.dataset.id);
            });

            articleForm.on('submit', function (e) {
                e.preventDefault();
                articleSubmit();
            });

            articleModal.on('hidden.bs.modal', function () {
                resetForm(articleForm);
            });
        });

        /**
         * Category
         */
        const categoryTable = $('#category-table');
        const categoryModal = $('#category-modal');
        const categoryForm = $('#category-form');
        const categoryTab = document.querySelector('#pills-category-tab');
        categoryTab.addEventListener('shown.bs.tab', function (event) {
            categoryDatatables()
        })

        const categoryDatatables = () => {
            generateDatatable({
                table: categoryTable,
                ajax: "{{ route('categories.index') }}",
                columns: [
                    {
                        data: 'name',
                        name: 'name',
                        width: '30%', 
                    },
                    {
                        data: 'user.name',
                        name: 'user.name',
                        width: '30%'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: '20%',
                        render: function (data) {
                            return formatDate(data, 'Y/m/d H:i');
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        width: '20%',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [
                    { targets: [0, 1, 2, 3],className: 'text-center' },
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 2, targets: -1 },
                ],
                initComplete: function (setting, json) {
                    $('#category-table_length').html(`
                        <button class="btn btn-sm bg-gradient-violet text-white"
                                id="category-add-btn"
                                title="Create Category">
                            <i class="fa-regular fa-square-plus fw-bold"></i>
                        </button>
                    `);

                    $('#category-table_wrapper')
                        .children(':first-child')
                        .addClass('d-flex justify-content-between mt-4 mb-1')
                        .removeClass('row');
                    
                    $('#category-table_wrapper')
                        .children(':first-child')
                        .children()
                        .removeClass('col-sm-12 col-md-6');

                    $('#category-add-btn').on('click', function () {
                        categoryAdd();
                    });        
                }
            })
        }

        const categoryAdd = () => {
            categoryForm.find('[name="_method"]').val('POST');
            categoryModal.modal('show');
        }

        const categoryEdit = (id) => {
            $.ajax({
                type: "GET",
                url: route('categories.edit', id),
                success: function (data) {
                    categoryForm.find('[name="_method"]').val('PUT');
                    categoryForm.find('#category-id').val(data.id);
                    categoryForm.find('#category-name').val(data.name);
                    categoryModal.modal('show');
                }
            });
        }

        const categorySubmit = () => {
            let id = categoryForm.find('#category-id').val();
            let r = (id) ? 'categories.update' : 'categories.store';
            let table = categoryDatatables;
            let modal = categoryModal;
            let form = categoryForm;
            submitData(r, table, modal, form, id);
        }

        const categoryDelete = (id) => {
            let table = categoryDatatables;
            categoryForm.find('[name="_method"]').val('DELETE');
            deleteData('categories.destroy', table, categoryModal, categoryForm, id);
        }

        $(function () {
            categoryTable.on('click', '.btn-edit', function () {
                categoryEdit(this.dataset.id);
            });
    
            categoryTable.on('click', '.btn-delete', function () {
                categoryDelete(this.dataset.id);
            });

            categoryForm.on('submit', function (e) {
                e.preventDefault();
                categorySubmit();
            });

            categoryModal.on('hidden.bs.modal', function () {
                resetForm(categoryForm);
            });
        });
    </script>
@endsection

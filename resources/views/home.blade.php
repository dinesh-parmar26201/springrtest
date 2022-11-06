<!doctype html>
<html lang="en">

<head>
    <title>Add New</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('datetimepicker/jquery.datetimepicker.css') }}">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h4>User Records</h4>
            </div>
            <div class="col-md-6">
                <button type="button" class="btn btn-primary btn-lg float-end" data-bs-toggle="modal"
                    style="--bs-btn-border-radius: 0rem;" data-bs-target="#exampleModal">
                    Add New
                </button>
            </div>
        </div>
        <div class="row mt-5">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th class="align-middle text-center" scope="col">Avtar</th>
                        <th class="align-middle text-center" scope="col">Name</th>
                        <th class="align-middle text-center" scope="col">Email</th>
                        <th class="align-middle text-center" scope="col">Experience</th>
                        <th class="align-middle text-center" scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <th scope="row"><img src="{{ asset('uploads/' . $user->image) }}" class="rounded-circle"
                                    alt="" height="100" width="100"></th>
                            <td class="align-middle text-center">{{ $user->full_name }}</td>
                            <td class="align-middle text-center">{{ $user->email }}</td>
                            <td class="align-middle text-center">{{ $user->experience }}</td>
                            <td class="align-middle text-center">
                                <div class="delete" data-id="{{ $user->id }}">
                                    <b>X</b> remove
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th scope="row" class="text-center" colspan="5">No records found !!!</th>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row text-center mb-5">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Records</h1>
                    </div>
                    <form action="" id="add_record_form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <label for="email">Email</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" name="email" id="email" required>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <label for="full_name">Full Name</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="full_name" id="full_name" required>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <label for="date_of_joining">Date Of Joining</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="date_of_joining" name="date_of_joining"
                                    class="form-control" />
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <label for="date_of_leaving">Date Of Leaving</label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id="date_of_leaving" name="date_of_leaving"
                                    class="form-control" />
                            </div>
                            <div class="col-sm-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="still_working"
                                        name="still_working">
                                    <label class="form-check-label" for="still_working">
                                        Still Working
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <label class="gender">Upload Image</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="file" name="image" id="image">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-12 text-center">
                                <div class="btn btn-warning" id="submit">Save</div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('datetimepicker/jquery.datetimepicker.full.js') }}"></script>
    <script>
        $('#date_of_joining').datetimepicker({
            timepicker: false,
            format: 'd/m/Y',
        });

        $('#date_of_leaving').datetimepicker({
            timepicker: false,
            format: 'd/m/Y',
        });

        $('#submit').on('click', function(e) {
            e.preventDefault();
            var finalFormdata = new FormData($('#add_record_form')[0]);
            $.ajax({
                url: "{{ route('store') }}",
                type: 'post',

                data: finalFormdata,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    location.reload();
                },
                error: function(data) {
                    console.log(data);
                    $('.text-danger').each(function() {
                        $(this).remove();
                    })
                    console.log(data);
                    $.each(data['responseJSON']['error'], function(key, value) {
                        $(`<span class='text-danger'>` + value + `</span>`)
                            .insertAfter(`#` + key);

                    })
                }

            });
        });

        $(".delete").on('click', function() {
            id = $(this).data('id');
            if (confirm("Are you sure?")) {
                $.ajax({
                    url: "{{ route('delete') }}",
                    type: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    dataType: 'json',
                    success: function(data) {
                        location.reload();
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }
            return false;
        })
    </script>
</body>

</html>

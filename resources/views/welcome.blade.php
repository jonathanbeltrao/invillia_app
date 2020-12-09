<!DOCTYPE html>
<html>
<head>
    <title>Invillia App Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>

<body>
<div class="container">

    <div class="panel panel-primary">
        <div class="panel-heading"><h2>Invillia App Test</h2></div>
        <div class="panel-body">

            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
                <img src="uploads/{{ Session::get('file') }}">
            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/api/upload" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="col-md-3">
                        <label for="">Customer XML</label>
                        <input type="file" name="customer_xml" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="">Order XML</label>
                        <input type="file" name="order_xml" class="form-control">
                    </div>

                    <div class="col-md-1">
                        <button type="submit" class="btn btn-success">Upload</button>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>
</body>

</html>

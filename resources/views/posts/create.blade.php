<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    </head>
    <body>
        <div class="card">
            <div class="card-body">
                {{-- must have enctype="multipart/form-data" to send a file --}}
            <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data"> 
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Post title</label>
                    <input type="text" class="form-control" id="title" name="title" >
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Post image</label>
                    <input type="file" class="form-control" id="image" name="image">
                </div>
                <div class="mb-3">
                    <label for="download" class="form-label">Download image</label>
                    <input type="file" class="form-control" id="download" name="download">
                </div>
                <div class="mb-3">
                    <label class="body" for="body">Body</label>
                    <textarea id="body" row="3" class="form-control" name="body">

                    </textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            </div>
        </div>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
</html>

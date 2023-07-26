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
        <div style="text-align: center; margin-top:20px">
            <a href="{{route("posts.create")}}">
                <button type="button" class="btn btn-primary" style="display: block; margin-left:20px">
                    Create
                </button>
            </a>
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Title</th>
                    <th scope="col">Image</th>
                    <th scope="col">Download</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                      <tr>
                        <td>{{$post->id}}</td>
                        <td>{{$post->title}}</td>
                        <td>
                            <img style="width: 50%; height:50%; max-width:100px;" src="{{ $post->getFirstMediaUrl('images') }}" class="img-fluid" alt="{{$post->title}}">
                        </td>
                        <td>
                            <img style="width: 50%; height:50%; max-width:100px;" src="{{ $post->getFirstMediaUrl('downloads') }}" class="img-fluid" alt="{{$post->title}}">
                        </td>
                        <td>
                            <div style="display: grid">
                                <a href="{{ route('download', $post->id) }}">
                                    <button type="button" class="btn btn-primary">Download</button>
                                </a>
                                <a href="{{ route('posts.edit', $post->id) }}">
                                    <button type="button" class="btn btn-success">Edit</button>
                                </a>
                                <form method="POST" action="{{ route('posts.destroy', $post->id) }}" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" >
                                        Delete
                                    </button>
                                </form>
                                
                            </div>
                        </td>
                      </tr> 
                    @endforeach
                </tbody>
            </table>
            <a href="{{route("downloadAll")}}">
                <button type="button" class="btn btn-secondary btn-lg" style="position: absolute; right: 20px;">
                    Download All
                </button>
            </a>
        </div>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
</html>

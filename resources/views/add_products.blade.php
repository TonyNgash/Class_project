<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>

    <div class="container">
        <div class="row">
            <h1 class="text-center">Upload Products</h1>
            <div class="card">
                <div class="card-header">
                    <h4>Fill in form below</h4>
                </div>
                <div class="card-body">
                    <form action="/save-product" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Product Name" name="title">
                        </div>
                        <div class="mb-3">
                            <input type="number" class="form-control" placeholder="Price" name="price">
                        </div>
                        <div class="mb-3">
                            <input type="number" class="form-control" placeholder="Quantity" name="qty">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Product Description</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <input type="file" class="form-control" name="image">
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-success" type="submit">upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <h1>image</h1>
            <img src="{{ url('storage/images/nX0tsL6m3TBeMu1bFSyxiQmMApHXjvWbs0btDjr2.jpg') }}" alt="" title="" />
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>

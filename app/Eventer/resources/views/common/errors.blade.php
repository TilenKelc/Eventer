@if (count($errors) > 0)
<div class="alert alert-danger" role="alert">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Whoops! Something went wrong!</strong>
    <br><br>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@elseif(!empty($successMssg))
    <div class="alert alert-success" role="alert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ $successMssg }}
    </div>
@elseif(!empty($errorMssg))
    <div class="alert alert-danger" role="alert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ $errorMssg }}
    </div>
@elseif(session()->get("successMssg") != null)
    <div class="alert alert-success" role="alert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session()->get("successMssg") }}
    </div>
    <?php session(['successMssg' => null]) ?>
@elseif(session()->get("errorMssg") != null)
    <div class="alert alert-danger" role="alert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session()->get("errorMssg") }}
    </div>
    <?php session(['errorMssg' => null]) ?>
@endif
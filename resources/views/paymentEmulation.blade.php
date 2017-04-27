<!DOCTYPE html>
<html lang="en">
<head>

    <link href="/components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">

</head>
<body>

    <h1>{{$type}} payment</h1>
    <div style="text-align: center">
        <form method="post" action="/payment/emulation/{{$type}}/{{$id}}/end">
            <input type="hidden" name="redirect" value="{{$redirect}}" />
            <button class="btn btn-default"  type="submit" name="result" value="success" >Success</button>
            <button class="btn btn-default"  type="submit" name="result" value="fail">Fail</button>
        </form>
    </div>
</body>
</html>

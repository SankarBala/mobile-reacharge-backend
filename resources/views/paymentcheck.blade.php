<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payment Status</title>
</head>

<body onunload="ok()">
    <h2>Payment Status</h2>
    <a href="#" result="allow" onclick="return CloseMySelf(this);">Allow</a>
    <a href="#" result="disallow" onclick="return CloseMySelf(this);">Don't Allow</a>
</body>
<script type="text/javascript">
    function CloseMySelf(sender) {
        try {
            window.opener.HandlePopupResult(sender.getAttribute("result"));
        } catch (err) {}
        window.close();
        return false;
    }

    function ok(){
        alert("OK");
    }
</script>

</html>

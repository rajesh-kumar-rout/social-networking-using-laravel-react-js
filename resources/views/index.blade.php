<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index</title>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @livewireStyles
</head>
<body>
    @livewire('counter', ['count' => 10])
    @livewireScripts

    <script>
        function show(event) {
            document.querySelector("#show").style.display == "none" ? 
            document.querySelector("#show").style.display = "block" :
            document.querySelector("#show").style.display = "none"
        }
    </script>
</body>
</html>
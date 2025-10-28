<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Fribera</title>
</head>
<body>
    <form action="{{ route('auth')}}" method="post" id="formLogin">
        @csrf
        <p>Faça seu login</p>
        <label for="re">Email<input type="text" id="re" name="email" maxlength='50'></label>
        <label for="password">Senha<input type="password" id="password" name="password" maxlength='200'></label>
        @error('email')
        <span style="color:red;">{{ $message }}</span>
        @enderror
        <input type="submit" value="Entrar" id="enviar"> 
        <a href="#">Esqueci minha senha</a>

        <label for="search"></label>
    </form>
</body>
</html>
<html>
<body>
	<h1>Hello world</h1>
	<div id='github'></div>

<script>
	fetch('https://api.github.com/users/evgeny-net-x', {
	  headers: new Headers({  // устанавливаем заголовки
	    'User-agent': 'Chrome/64.0 My Own Agent'
	  })
	})
	.then(response => response.json())   // получаем ответ в виде промиса
	.then(data => {
	  document.getElementById('github').innerHTML = JSON.stringify(data)
	})
	.catch(error => console.error(error)) // или ошибку, если что-то пошло не так
</script>

<?php

foreach ($_SERVER as $key => $value) {
	echo '<strong>' . $key . '</strong>: ' . $value . "\n";
	echo '<br>';
}

?>

</body>
</html>


<?php

$url = 'https://scontent-muc2-1.cdninstagram.com/v/t51.2885-15/483497398_18394187995128239_665682359657220725_n.jpg?stp=dst-jpg_e35_s1080x1080_tt6&efg=eyJ2ZW5jb2RlX3RhZyI6ImltYWdlX3VybGdlbi4xNDQweDE0NDAuc2RyLmY3NTc2MS5kZWZhdWx0X2ltYWdlIn0&_nc_ht=scontent-muc2-1.cdninstagram.com&_nc_cat=111&_nc_oc=Q6cZ2AEeeaYZdeHKyRtozrUeci31DpZ_fYm5lvpw49TJ2V1wMgwjbv5BhG36difbINKBgy8&_nc_ohc=36_kdh0hAcAQ7kNvgHtMj6e&_nc_gid=1a4a5140221c42a4ad8a0b7153ced617&edm=AMKDjl4BAAAA&ccb=7-5&ig_cache_key=MzU4NjYwNzY1ODMwMDA0MzgxNQ%3D%3D.3-ccb7-5&oh=00_AYHWRU3JAXZc-BaOx3lzLkDy5Eqy2xl4pXQrQLA7T6LwNA&oe=67D743B1&_nc_sid=472314';

$imageData = base64_encode(file_get_contents($url));
echo "<img src='https://lookaside.instagram.com/seo/google_widget/crawler/?media_id=3374333178423360502' />";
?>
# bandcamp_album
A php class for get some informations about a bandcamp album from an url.

For performances, this class use another class, a cache system :
https://github.com/DevIl00110000/cache_system

If you have a question or just want talk with me, you can come on [my Twitter](https://twitter.com/DevIl00110000).

## How to start ?
You need a ssl certificate with an extension  ".cer" on Base 64.

I advise you to put the certificate file with the class.
``` php
$album = new bandcamp_album(
	"https://prettyoliviarecords.bandcamp.com/album/dead-calm",
	__DIR__.DIRECTORY_SEPARATOR."cert.cer"
);
```

### It's an album
You can know if it's an album url like that :
``` php 
if (bandcamp_album::is_album("your url")) {
	// Code ...
}
```
### get_nameBand()
``` php
$album->get_nameBand();
```
Return **The boys with the perpetual nervousness**

### get_pictureBand()
``` php
$album->get_pictureBand();
```
Return **https://f4.bcbits.com/img/0002152283_10.jpg**

If it's a label, return the label image.

### get_nameAlbum()
``` php
$album->get_nameAlbum();
```
Return **Dead Calm**

### get_cover()
``` php
$album->get_cover();
```
Return (with var_dump)
``` php
**array** _(size=13)_
  0 => string 'https://f4.bcbits.com/img/a2712549317_3.jpg' _(length=43)_
  1 => string 'https://f4.bcbits.com/img/a2712549317_8.jpg' _(length=43)_
  2 => string 'https://f4.bcbits.com/img/a2712549317_15.jpg' _(length=44)_
  3 => string 'https://f4.bcbits.com/img/a2712549317_12.jpg' _(length=44)_
  4 => string 'https://f4.bcbits.com/img/a2712549317_7.jpg' _(length=43)_
  5 => string 'https://f4.bcbits.com/img/a2712549317_11.jpg' _(length=44)_
  6 => string 'https://f4.bcbits.com/img/a2712549317_9.jpg' _(length=43)_
  7 => string 'https://f4.bcbits.com/img/a2712549317_4.jpg' _(length=43)_
  8 => string 'https://f4.bcbits.com/img/a2712549317_2.jpg' _(length=43)_
  9 => string 'https://f4.bcbits.com/img/a2712549317_14.jpg' _(length=44)_
  10 => string 'https://f4.bcbits.com/img/a2712549317_13.jpg' _(length=44)_
  11 => string 'https://f4.bcbits.com/img/a2712549317_16.jpg' _(length=44)_
  12 => string 'https://f4.bcbits.com/img/a2712549317_10.jpg' _(length=44)_
```

### get_description()
``` php
$album->get_description();
```
Return (with var_dump)
``` php
string 'Here goes the first song out of &#39;Dead Calm&#39;, the debut LP of The Boys With The Perpetual Nervousness. It is &quot;Close The Doors&quot;, a simple pop song featuring 12-string guitar arrangements and, of course, the delicious voice of Andrew. The official release date of the album in vinyl is next March 1st, but you can already buy it at the label&#39;s store. As of March 8th, the album will be posted on Spotify and similar platforms. There is also a video!<br><br>TBWTPN are Andrew Taylor (Dropk'... _(length=823)_
```

### get_player()
``` php
$album->get_player();
```
Return (with var_dump)
``` php
array (size=2)
  0 => string '<iframe src="https://bandcamp.com/EmbeddedPlayer/album=2885614535/size=large/bgcol=333333/linkcol=0f91ff/transparent=true/" seamless></iframe>' (length=142)
  1 => string '<iframe src="https://bandcamp.com/EmbeddedPlayer/album=2885614535/size=large/bgcol=333333/linkcol=0f91ff/minimal=true/transparent=true/" seamless></iframe>' (length=155)
```
This function return an array with two different player.

### end()
``` php
$album->end();
```
Delete the cache file.

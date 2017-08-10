Framework
==
Date: 2017.06.26
Auther: Yugeta.Koji
-- @

# Summery
ブログ構築用フレームワーク
フォルダをコピーすればすぐに使い始められるフレームワーク

# Functions
初期設定画面
Login
	管理画面用
画像アップロード機能
ログデータ管理
SQL対応（デフォルトはテキストデータ対応）
負荷分散対応（設計思想の構築）
画像遅延LOAD
GoogleAnalyticsタグ挿入処理
オンラインアップデート機能（gitとは別）
API設計（各種機能に外部からURLアクセスできる）
tokenなどの情報をセットする機能

# Hierarchy
/
┣ data/	#SQLを使う場合は不要
┃
┣ design/
┃	┣ sample
┃	┗ blog
┃
┣ library/
┃	┣ bootstrap
┃	┗ js
┃
┣ plugin/   #systemはシステム
┃	┗ sample
┃
┣ system/
┃	┣ php
┃	┗ js
┃
┗ index.php #起動モジュール

# フォルダ要件
- design
任意のひとつのみ実行できる。
ログインユーザーの条件により、デザインを切り替えることができる。

- plugin
摘要、停止フラグを別途設定ができ、摘要対象のフォルダはphpを自動でrequireされる。

- library
BootStrap (jQuery)

- system
基本システムで利用するモジュール群


# Specification
セキュリティ保持の為にdataフォルダの場所はindex.phpに記述でき、任意の場所に設置できる。
dataフォルダが無い、又はdata/user.jsonが無い場合は、初期設定画面が立ち上がる

# 主要SYstem-Functions
RepTag
    システムタグをリプレイスすることができる。
infoURL
    URLに関する情報を取得できる。

# 概要
- 修正
デザインテンプレート
index.phpの認証フロー

- 追加
ログイン認証機能
アカウント仮登録機能
アカウント登録メール送信機能
アカウント正式登録機能

# 初期設定
?plugins=config

# 機能
1.商品ラインナップ
2.マニュアルPDFリンク
3.商品お知らせ
4.Q&Aクロール（カカクコム、amazon、他）

htmlページ情報
指定がなければデフォルトではindex.html
ログインページはlogin.html
ブログページは、blog.html
それぞれ、p=###のページ番号指定で、コンテンツ内容を表示できる。
UTL :
	http://hoge.com/?p=001
	http://hoge.com/?p=001&h=blog
	#blogとindex.htmlは同じものでも可

# 仕様
1.商品ラインナップから商品一覧のURL取得
2.商品別マニュアルのURL取得
    #サイト内リンクからPDFリンクを取得※PDFが設置されているページURLも取得

3.パンくず構造（マニュアル付随情報）
    カテゴリ

    【メーカー情報】
    メーカー（社名・ブランド）
    商品名
    型番


    【詳細情報】
    -発売日
    -メーカー保証期間

    【ユーザー情報】
    -購入日
    -購入先
    -購入金額
    -知識レベル

    【共有情報】
    +裏ワザ
    +

# ファイル構成

## data
items.json (商品一覧)
sites.json (WEBサイト[array])
manuals.json (マニュアルページ[array])
brand.json (メーカー・ブランド一覧)

# フロー




# 使い方

# Word
data : データベースに格納するデータを指す
system : フレームワークの基礎システム
plugin : 各種機能
theme : デザイン要素
user : システムにログインできるユーザー
viewer : コンテンツを閲覧する人

# URL
http://hoge.com/
http://hoge.com/index.php
http://hoge.com/?p=000001           : page
http://hoge.com/?tag=tech
http://hoge.com/?grp=program
http://hoge.com/?m=login , logout   : mode
http://hoge.com/?api=**

# Query
p=***		// data/page/source/***.dat
h=***		// design/*target*/***.html


## ページからdesignの個別モジュールにアクセスする方法
-- JS
http://hoge.com/?js=js/test.js
-- PHP
http://hoge.com/?php=php/test.php

## 表示時に各種プラグインを起動する場合
classをnewしてfunctionを実行する。
pluginは、自動実行をしない構成が良い

## srcやhrefの記述


# 画面

- 初期設定
	タイトル
	データベース
	管理者ログイン設定（ID,PW登録）


- ブログ表示

- ログイン
- ログイン失敗
【ログイン後】
	- 管理者モード
	- コンフィグ設定
	- ページ編集
	- プラグイン設定
	- デザイン設定
	- システム設定
	- ライブラリ設定
	- データ管理
	- アクセスレポート


# プラグインで出来ること
- SNSリンクボタン
- GA設定
- 各種ガジェット
-



# その他

# version
wrote  @ yugeta.koji
ver0.1 @ 2015.04.11
ver0.2 @ 2015.05.20
framework/ver2 @ 2015. 5.31
ver0.3 @ 2015.06.26

##########
## 要望


* 一般ユーザー登録
- ログインID、PWを登録してその後にMLなどを受け取れるしくみ

* design入れ替えで、表示、機能などが入れ替えられる仕組み
- systemモジュールをdesignに以降
- designの必要最低限モジュール構成の確率

* pluginの追加、削除で、機能の出し入れができる仕組み
- 見た目機能
- 裏の機能

* 編集権限
- design,pluginの編集権限（ファイルを直接修正しないと出来ない）
- ブログ、ページの記事、内容作成・修正（admin管理画面から行える）
- pluginやdesignパターンの変更や個別の修正（admin管理画面からログインして権限社であれば行える）

* 画像アップロードの際に複数サイズの自動作成
- w:300px,600px,1200px,
- 元が500pxの場合は300pxしか作られない
- 呼び出し時に指定できる
- 後から変更できる
- 元画像のサイズ情報をinfoに加える

* 権限
- ページグループ毎に、ログインアカウントで権限を分けたい
- AページはAさん、BページはBさんの権限で編集とか・・・



## Authority Lists

- [normal] 一般ユーザー
- [member] 一般ユーザー（ログイン）記事投稿者（ログイン必須）
- [master] ページ管理者（ログイン必須）
- [authority] 投稿承認者
- [administrator] システム管理者



## Access URL

# Hierarchy


- root
URL : http://hoge.com/
index.php
	┗ system/php/**
			┗ design/%sample%/index.html
					┗ data/page/default/top.html

- blog
x http://hoge.com/?bog=123456
- http://hoge.com/?b=blog&p=123456 -(省略)-> http://hoge.com/?p=123456
index.php
	┗ system/php/**
			┗ design/%sample%/index.html
				┗ design/%sample%/page/blog.html
						┗ data/page/blog/***.json

- system (must logined)
x http://hoge.com/?system=login
- http://hoge.com/?b=system&p=login
index.php
	┗ system/php/**
			┗ design/%sample%/index.html
					┗ system/page/***.html

- Other...
x http://hoge.com/?etc=***
- http://hoge.com/?b=etc&p=***
index.php
	┗ system/php/**
			┗ design/%sample%/index.html
				┗ design/%sample%/page/etc.html
					┗ data/page/etc/***.json

# Design-Pattern

index.html

<html>
	<head>
		<*header.html*>
	</head>
	<body>
		<base.html(navigation-menu)>

		<*contents*>

		<*right|left-menu*>

		<*footer*>

	</body>
</html>

# 考え方

- [クエリ]
- b(base)は省略すると"blog"がデフォルト値
- p(page)は省略すると "top"がデフォルト値
- b=blog -> data/page/blog/**	(ブログ記事)
- b=system -> system/page/**  (変更不可)
- b=etc -> data/page/etc/**		(任意フォルダにページ格納可能)

- 基本的にページデータは、固定ページも含めてdataフォルダに保存されているべき
-
- 記事、更新情報などがある場合は、dataフォルダにページデータ（コンテンツ部分）を設置して、システムページは変更不可のsystem、固定ページ


## Plugin仕様

- 自動実行に関するclass名をplugin名と同じにする。
-


## Plugin導入パターン

・記事のdocument-headタグ内にjsタグを挿入して機能追加（JSライブラリ）
・記事の特定の箇所にガジェット表示（ヘッダ、コンテンツ上部、コンテンツ下部、左右メニュー任意箇所、フッタ上部）
・PHPの関数ライブラリ
・管理画面の機能追加


## URL-pattern

- top -> blog-list (default/html/index.html -> data/page/default/pageList.html | index.html)
http://myntstudio.mynt.site/

- blog -> article (default/html/blog.html -> data/page/blog/001.html)
http://myntstudio.mynt.site/?blog=001

- privacy (default/html/index.html -> data/page/default/privacy.html)
http://myntstudio.mynt.site/?default=privacy

- system
http://myntstudio.mynt.site/?system=001
http://myntstudio.mynt.site/system.php?p=001

- Other
http://myntstudio.mynt.site/?p=001

- 強制ページ表示
  templateFile
	?templateFile=default/**/html/%templateFile%.html

	contentsPath
	?contentsPath=plugin/***.html

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

## ページからdesignの個別モジュールにアクセスする方法
-- JS
http://hoge.com/?js=js/test.js
-- PHP
http://hoge.com/?php=php/test.php

## 表示時に各種プラグインを起動する場合
classをnewしてfunctionを実行する。
pluginは、自動実行をしない構成が良い

## srcやhrefの記述



# その他

# version
wrote  @ yugeta.koji
ver0.1 @ 2015.04.11
ver0.2 @ 2015.05.20
framework/ver2 @ 2015. 5.31
ver0.3 @ 2015.06.26

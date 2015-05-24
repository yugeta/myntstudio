# login
    wrote  @ yugeta.koji
    ver0.1 @ 2015. 4.11
    ver0.2 @ 2015. 5.20 :

# 概要
    ## 修正
    デザインテンプレート
    index.phpの認証フロー

    ## 追加
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

# 仕様

    1.商品ラインナップから商品一覧のURL取得
    2.商品別マニュアルのURL取得
        *サイト内リンクからPDFリンクを取得※PDFが設置されているページURLも取得

    3.パンくず構造（マニュアル付随情報）
        カテゴリ

        【メーカー情報】
        メーカー（社名・ブランド）
        商品名
        型番


        【詳細情報】
        *発売日
        *メーカー保証期間

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


# その他

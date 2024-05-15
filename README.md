# 概要

portfolio-bookをLaravelを使って作り直したものです。<br>
設計書はdocフォルダに入っています。

# 開発環境

Windows 10 Version 22H2
PHP 8.0.15<br>
Laravel 9.27.0<br>
SQLite 3.35.5<br>
jQuery 3.6.1<br>

# portfolio-bookからの変更点

* 推薦文一覧ページでのペジネーション使用
* ログアウト状態でも推薦文閲覧が可能
* 推薦文投稿時に良かった点を選ぶ機能の追加<br>
良かった点の選択、推薦文入力いずれかのみでの投稿も可能
* 参考になった推薦文を記録する機能追加<br>
各推薦文ごとに参考にした人の人数を表示するようにしました。<br>
推薦文の表示順序を新着順と参考にした人の多い順から選択できるようにしました。
* 読みたい本の追加処理でAjaxを使用するように変更

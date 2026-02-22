# Ouchi Inventory（おうち在庫）

家庭の在庫を管理するWebアプリケーションです。ジャンル別にアイテムを登録し、数量の増減・次回購入リスト・家族との共有ができます。

## 機能一覧

- **ユーザー認証** … 登録・ログイン・プロフィール編集（Laravel Breeze）
- **ダッシュボード** … ジャンルごとのタブで在庫一覧を表示
- **ジャンル管理** … ジャンル（例：食材、日用品）の作成・編集・削除
- **アイテム管理**
  - ジャンルごとにアイテムの登録・編集・削除
  - 数量の +1 / −1、手動入力での更新
  - 備考メモ、よく使う順のソート
- **次回購入リスト** … アイテムを「次回購入」に追加し、買い物後に完了・キャンセル
- **共有** … 他のユーザーに自分の在庫を共有（閲覧のみ）。共有された一覧の表示

## 技術スタック

| 項目 | 技術 |
|------|------|
| バックエンド | PHP 8.2+, Laravel 12 |
| 認証・UI | Laravel Breeze |
| フロントエンド | Blade, Alpine.js, Tailwind CSS |
| ビルド | Vite 7 |
| データベース | SQLite（既定） / MySQL 等に対応 |
| デプロイ | Docker（Fly.io 向け Dockerfile 付き） |

## 必要環境

- PHP 8.2 以上
- Composer 2
- Node.js 18 以上（npm）
- SQLite または MySQL

## セットアップ

### 1. リポジトリのクローン

```bash
git clone https://github.com/your-username/ouchi-inventory.git
cd ouchi-inventory
```

### 2. 依存関係のインストール

```bash
composer install
cp .env.example .env
php artisan key:generate
```

### 3. データベースの準備

`.env` で `DB_CONNECTION=sqlite` のまま利用する場合：

```bash
touch database/database.sqlite
php artisan migrate
```

MySQL を使う場合は `.env` で `DB_CONNECTION=mysql` とホスト・DB名・ユーザー・パスワードを設定してから `php artisan migrate` を実行してください。

### 4. フロントエンドのビルド

```bash
npm install
npm run build
```

### 5. 開発サーバーの起動

```bash
php artisan serve
```

ブラウザで `http://localhost:8000` にアクセスし、ユーザー登録から利用を開始できます。

### 一括セットアップ（初回のみ）

```bash
composer run setup
```

`.env` の作成、キー生成、マイグレーション、`npm install`、`npm run build` まで一括で実行します。

### 開発時（サーバー・キュー・ログ・Vite を同時起動）

```bash
composer run dev
```

## テスト

```bash
composer run test
# または
php artisan test
```

## Docker でのビルド

プロジェクトには Fly.io 向けのマルチステージ `Dockerfile` が含まれています。`.fly` ディレクトリに nginx / PHP-FPM / エントリポイント等の設定が必要です。通常の Docker 利用時は、必要に応じて Dockerfile や `.dockerignore` を編集して利用してください。

## ディレクトリ構成（主要部分）

```
app/
├── Http/Controllers/   # Inventory, Genre, Item, Purchase, Share, Profile
├── Models/            # User, Genre, Item, Purchase, Share
├── Services/          # ShareService（共有・アクセス権判定）
database/migrations/   # users, genres, items, purchases, shares 等
resources/views/       # dashboard, genres, items, purchases, shares, auth
routes/web.php        # 認証済みルート・リソースルート
```

## ライセンス

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---
name: Supabase自動ping設定
description: Supabase無料プランの自動停止を防ぐGitHub Actionsワークフロー
type: project
---

Supabase無料プランは7日間未使用で自動停止される。これを防ぐためGitHub Actionsで3日に1回自動pingを実行する仕組みを導入済み。

**ワークフローファイル:** `.github/workflows/keep-supabase-alive.yml`
**GitHub Secrets:** `SUPABASE_URL`, `SUPABASE_ANON_KEY` を登録済み
**Supabase Project ID:** `izycmpeyxkdsozweychz`
**スケジュール:** 3日に1回、UTC 0:00（JST 9:00）
**pingエンドポイント:** `/rest/v1/genres?select=id&limit=1`（2026-03-26に `/rest/v1/` から変更。Supabaseが2026-04-08にルートエンドポイントへのanon keyアクセスを廃止するため）

**Why:** 実運用はないが、停止→復元の手間を避けるため
**How to apply:** ワークフローはActions タブで実行履歴・手動実行が可能

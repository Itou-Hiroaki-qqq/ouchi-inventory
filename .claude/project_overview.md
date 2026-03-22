---
name: Ouchi Inventory プロジェクト概要
description: 家庭在庫管理Webアプリの概要・技術スタック・主要機能
type: project
---

家庭の在庫を管理するWebアプリケーション「おうち在庫」。

**技術スタック:** PHP 8.2+ / Laravel 12 / Laravel Breeze / Blade + Alpine.js + Tailwind CSS / Vite 7 / SQLite（既定）/ Docker（Fly.io向け）

**主要機能:**
- ユーザー認証（Laravel Breeze）
- ジャンル管理（食材、日用品など）
- アイテム管理（数量増減、備考、ソート）
- 次回購入リスト
- 他ユーザーへの在庫共有（閲覧のみ）

**主要モデル:** User, Genre, Item, Purchase, Share
**サービス:** ShareService（共有・アクセス権判定）

**Why:** 家庭での在庫切れ防止・買い物リスト管理・家族間共有のため
**How to apply:** Laravel Breeze + Blade構成のCRUDアプリとして機能追加・修正を行う

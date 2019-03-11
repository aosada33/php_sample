
主に5.6→7.2間の変更について

## 下位互換性の無い変更(主に自分に影響しそうな箇所のみ)

詳細は 
http://php.net/manual/ja/migration70.php
http://php.net/manual/ja/migration71.php
http://php.net/manual/ja/migration72.php

- 間接的なアクセスの評価順の変更。厳密に左から右に評価するように
- foreach は内部の配列ポインタを変更しない
- 参照渡しの foreach処理中に追加した要素も反復処理の対象に含まれるように
- ゼロ除算時にE_WARNING発生からDivisionByZeroError 例外をスローに変更
- 十六進形式の数値を含む文字列は数値とはみなされなくなった
- func_get_arg(), func_get_args(), debug_backtrace()で取得できる値がパラメータ渡し時の値から取得時の値に変更
- INI ファイルにおける # 形式のコメントが削除。;を使う
- JSON 拡張モジュールが JSOND に置き換わる。空の文字列は妥当な JSON だとみなされなくなりました。
- mt_rand() のアルゴリズムのバグ修正。
- MCrypt 拡張モジュールが PECL に移動。openssl使用が推奨。暗号化アルゴリズムの互換性で判断する。

## iniファイルの変更

|設定値|php5|php7|
| --- | --- | --- |
| serialize_precision | 17 (php5.3までは100) | -1 |
| default_charset | 未設定 | UTF-8 |
| pcre.jit |<span style="color: #aaa">※php7.0で追加</span> | 0 | 
| sql.safe_mode | Off | <span style="color: #aaa">※php7.2で削除</span> |
| session.hash_function | 0 | <span style="color: #aaa">※php7.1で削除</span> |
| session.sid_length | <span style="color: #aaa">※php7.1で追加</span> | 26 |
| session.trans_sid_tags | <span style="color: #aaa">※php7.1で追加</span> |  "a=href,area=href,frame=src,form=" |
| session.hash_bits_per_character | 5 | <span style="color: #aaa">※php7.1で削除</span> |
| session.sid_bits_per_character | <span style="color: #aaa">※php7.1で追加</span> | 5 |
| url_rewriter.tags | "a=href,area=href,frame=src,input=src,for" | <span style="color: #aaa">7.1以降で未使用(削除ではないぽい)</span> |
| zend.assertions | <span style="color: #aaa">※php7.1で追加</span> | -1 |

## php5系からの移行時互換維持のためのini設定

### pcre.jit
PCRE の just-in-time コンパイルを利用するかどうか。
1を設定してJITコンパイルを使用した方が早いが、下記のような問題に遭遇することがあるらしいので、互換性という点では0が無難か。
https://qiita.com/ryosukes/items/3f526ff0dd3fc4f9c3b5

### session.sid_length
セッションIDの長さを決めるための設定値。128ビット以上になるように設定する。
php5系のiniファイルを見て、
session.hash_func=0 (MD5) で session.hash_bits_per_character=4 の場合は 32
session.hash_func=1 (SHA1) で session.hash_bits_per_character=6 の場合は 32
session.hash_func=0 (MD5)  で session.hash_bits_per_character=5 の場合は 26
session.hash_func=0 (MD5)  で session.hash_bits_per_character=6 の場合は 22
を設定する。

### serialize_precision
小数値(float型)をserialize化する際に格納する桁数を指定。
シリアライズ化した結果に影響する。
また、serialize_precisionの値が同じでもphpのバージョンによって結果が変わる。
(マイナーバージョンで変わるかは試してない)
```
$num = 3.14を下記の方法で出力

■php5.5で実行時
-- serialize_precision:17 (php5.5のデフォルト) --------------------------
  var_dump    : float(3.14)
  string      : 3.14
  var_export  : 3.1400000000000001
  serialize   : d:3.1400000000000001;
  json_encode : 3.14

-- serialize_precision:-1 (php7.2のデフォルト) --------------------------
  var_dump    : float(3.14)
  string      : 3.14
  var_export  : 3.1400000000000001
  serialize   : d:3.1400000000000001;
  json_encode : 3.14


■php7.2で実行時 
-- serialize_precision:17 (php5.5のデフォルト) --------------------------
  var_dump    : float(3.14)
  string      : 3.14
  var_export  : 3.1400000000000001
  serialize   : d:3.1400000000000001;
  json_encode : 3.1400000000000001

--  serialize_precision:-1 (php7.2のデフォルト) --------------------------
  var_dump    : float(3.14)
  string      : 3.14
  var_export  : 3.14
  serialize   : d:3.14;
  json_encode : 3.14
```
json_encodeが一番影響が大きいと思われるので -1設定にするのが無難。
キューにオブジェクトを投入したり、セッションにオブジェクトをシリアライズ化して保持したりしている際は要注意。（でも-1の結果が欲しかった結果だと思う。）
※php7系 -1, php5系16が良さそう。

## 非推奨機能を全部は消せないので、、、

error_reportでe_notice, duplicateを抑制して対処。

## APC -> OPcacheへ変更必要
php5までは実運用上は必須。php7以降もマイナーバージョンアップで特にパフォーマンス向上が顕著な項目なのでできれば使いたい

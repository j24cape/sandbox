#!/usr/bin/env python3
"""
Vonage Messages API CLI - シンプルなコマンドラインからVonage Messages APIを使用してSMSを送信するツール
"""
import os
import sys
import argparse
from pathlib import Path
import logging
from typing import Dict, Optional
import json
import requests

from dotenv import load_dotenv

# ロギングの設定
logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s - %(name)s - %(levelname)s - %(message)s",
)
logger = logging.getLogger("vonage-messages-cli")


class VonageMessagesClient:
    """VonageのMessages APIを扱うクラス"""

    def __init__(self, api_key: str, api_secret: str):
        """
        VonageMessagesClientの初期化

        Args:
            api_key: Vonage API Key
            api_secret: Vonage API Secret
        """
        self.api_key = api_key
        self.api_secret = api_secret
        self.base_url = "https://api.nexmo.com/v1/messages"

    def send_sms(self, sender: str, recipient: str, message: str) -> Dict:
        """
        SMSを送信する

        Args:
            sender: 送信者の電話番号または名前
            recipient: 受信者の電話番号（国際形式）
            message: 送信するメッセージ内容

        Returns:
            Dict: Vonage APIからのレスポンス
        """
        logger.info(f"Sending SMS from {sender} to {recipient}")
        
        headers = {
            "Content-Type": "application/json",
            "Accept": "application/json"
        }
        
        payload = {
            "from": sender,
            "to": recipient,
            "message_type": "text",
            "text": message,
            "channel": "sms"
        }
        
        try:
            response = requests.post(
                self.base_url,
                auth=(self.api_key, self.api_secret),
                headers=headers,
                json=payload
            )
            
            response.raise_for_status()  # エラーステータスのチェック
            
            response_data = response.json()
            logger.debug(f"API Response: {response_data}")
            
            return {
                "success": True,
                "message_id": response_data.get("message_uuid"),
                "response": response_data
            }
            
        except requests.exceptions.HTTPError as e:
            logger.error(f"HTTP Error: {e}")
            error_message = "Unknown error"
            try:
                error_data = response.json()
                error_message = error_data.get("detail", str(e))
            except:
                pass
            
            return {
                "success": False,
                "error": error_message
            }
            
        except requests.exceptions.RequestException as e:
            logger.error(f"Request Exception: {e}")
            return {
                "success": False,
                "error": str(e)
            }


def load_environment() -> Optional[Dict[str, str]]:
    """
    .envファイルから環境変数を読み込む

    Returns:
        Optional[Dict[str, str]]: 環境変数の辞書またはNone（失敗時）
    """
    script_dir = Path(__file__).resolve().parent.parent.parent
    env_path = script_dir / '.env'
    if not env_path.exists():
        logger.error(".envファイルが見つかりません")
        return None

    load_dotenv(env_path)
    
    # 必要な環境変数をチェック
    api_key = os.getenv("VONAGE_API_KEY")
    api_secret = os.getenv("VONAGE_API_SECRET")
    
    if not api_key or not api_secret:
        logger.error(".envファイルにVONAGE_API_KEYまたはVONAGE_API_SECRETが設定されていません")
        return None
    
    return {
        "api_key": api_key,
        "api_secret": api_secret,
    }


def parse_arguments():
    """
    コマンドライン引数をパースする

    Returns:
        argparse.Namespace: パースされたコマンドライン引数
    """
    parser = argparse.ArgumentParser(description="Vonage Messages API SMS送信CLI")
    
    parser.add_argument(
        "-f", "--from",
        dest="sender",
        required=True,
        help="送信者の電話番号または名前（例: 'ACME Inc'または'+818012345678'）"
    )
    
    parser.add_argument(
        "-t", "--to",
        dest="recipient",
        required=True,
        help="受信者の電話番号（国際形式 例: '+818012345678'）"
    )
    
    parser.add_argument(
        "-m", "--message",
        required=True,
        help="送信するメッセージの内容"
    )
    
    parser.add_argument(
        "-v", "--verbose",
        action="store_true",
        help="詳細なログ出力を有効にする"
    )
    
    return parser.parse_args()


def main():
    """メイン関数"""
    args = parse_arguments()
    
    # 詳細ログ設定
    if args.verbose:
        logger.setLevel(logging.DEBUG)
        logger.debug("詳細ログモードが有効になりました")
    
    # 環境変数の読み込み
    env_vars = load_environment()
    if not env_vars:
        logger.error("環境変数の読み込みに失敗しました。処理を中止します。")
        sys.exit(1)
    
    # Messagesクライアントの初期化
    client = VonageMessagesClient(
        api_key=env_vars["api_key"],
        api_secret=env_vars["api_secret"]
    )
    
    # SMSの送信
    result = client.send_sms(
        sender=args.sender,
        recipient=args.recipient,
        message=args.message
    )
    
    # 結果の出力
    if result["success"]:
        print(f"メッセージが正常に送信されました。メッセージID: {result['message_id']}")
        if args.verbose and "response" in result:
            print(f"APIレスポンス詳細: {json.dumps(result['response'], indent=2, ensure_ascii=False)}")
    else:
        print(f"メッセージの送信に失敗しました: {result['error']}")
        sys.exit(1)


if __name__ == "__main__":
    main()

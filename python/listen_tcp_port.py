#!/usr/bin/python3
# -*- coding: utf-8 -*-

# ほとんど github copilot による自動生成です。

import sys
import socket
import threading

# 使い方
# python3 listen_tcp_port.py 8080
def usage():
    print("Usage: python3 listen_tcp_port.py <port>")
    exit(1)

# メイン処理
def main():
    if len(sys.argv) != 2:
        usage()

    port = int(sys.argv[1])
    #host = 'localhost'
    host = ''

    with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
        s.bind((host, port))
        s.listen(1)
        print(f'Listening on {host}:{port}...')
        conn, addr = s.accept()
        with conn:
            print(f'Connected by {addr}')
            while True:
                data = conn.recv(1024)
                if not data:
                    break
                print(f'Received: {data.decode()}')

def loop():
    try:
        while True:
            pass
    except KeyboardInterrupt:
        print('KeyboardInterrupt')
        exit(0)
    except Exception as e:
        print(f'Error: {e}')
        exit(1)

if __name__ == '__main__':
    # メイン処理を別スレッドで実行する
    thread = threading.Thread(target=main, daemon=True)
    thread.start()

    # スレッドの終了を待つ
    loop()

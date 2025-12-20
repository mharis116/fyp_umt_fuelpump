# cli.py
import sys
from chatbot.chatbot import HTSAXONChatbot

def main():
    print("=== HTSAXON Chatbot CLI ===")
    print("Type 'exit' to quit\n")

    bot = HTSAXONChatbot()

    while True:
        query = input("You: ").strip()
        if query.lower() in ["exit", "quit"]:
            print("Exiting...")
            break

        try:
            response = bot.answer(query)
            print(f"Bot: {response}\n")
        except Exception as e:
            print(f"Error: {e}\n")

if __name__ == "__main__":
    main()

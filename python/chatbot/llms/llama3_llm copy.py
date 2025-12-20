from llama_cpp import Llama

class Llama3LLM:
    def __init__(self, model_path="/home/htsaxon/Desktop/python/llm/models/Meta-Llama-3-8B-Instruct-Q5_K_M.gguf",
                 n_ctx=8192, n_threads=8, n_batch=512, n_gpu_layers=1, verbose=False):
        """
        Initialize the LLaMA LLM.

        Parameters:
        - model_path: path to the GGUF model file
        - n_ctx: context size (tokens)
        - n_threads: CPU threads to use
        - n_batch: number of tokens to process per step
        - n_gpu_layers: number of model layers to move to GPU
        - verbose: whether to show detailed logs
        """
        self.llm = Llama(
            model_path=model_path,
            n_ctx=n_ctx,
            n_threads=n_threads,
            n_batch=n_batch,
            n_gpu_layers=n_gpu_layers,
            verbose=verbose,
            
            stop=["\n\n", ""],  # Add stop sequences
        )

    def generate(self, prompt, max_tokens=80, temperature=0.7, top_p=0.9, top_k=50, repeat_penalty=1.1):
        """
        Generate text from the model in streaming mode.

        Returns the generated text as a string.
        """
        output = self.llm(
            prompt,
            # max_tokens=max_tokens,
            temperature=temperature,
            top_p=top_p,
            top_k=top_k,
            repeat_penalty=repeat_penalty,
            stream=True
        )

        result = ""
        for token in output:
            result += token["choices"][0]["text"]
        return result
    
    

if __name__ == "__main__":
    # Example usage
    model = Llama3LLM(
        model_path="/home/htsaxon/Desktop/python/llm/models/Meta-Llama-3-8B-Instruct-Q5_K_M.gguf",
        n_ctx=8192,
        n_threads=8,
        n_gpu_layers=1
    )

    text = model.generate("Write an essay on my father")
    print(text)

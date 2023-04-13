# Experimental bindings for LLama C++ library

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kambo/llama-cpp.svg?style=flat-square)](https://packagist.org/packages/kambo/llama-cpp)
[![Tests](https://img.shields.io/github/actions/workflow/status/kambo-1st/llama-cpp-php/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/kambo-1st/llama-cpp-php/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/kambo/llama-cpp.svg?style=flat-square)](https://packagist.org/packages/kambo/llama-cpp)

The package enables the use of the LLama C++ library in PHP, thereby allowing the setup and execution of LLM models in PHP on your local machine.

**This is highly experimental and not suitable for production use!**

**Use at your own risk!**

**Only Linux is supported!**

## Installation

You can install the package via composer:

```bash
composer require kambo/llama-cpp-php
```

## Usage

Get model, you can use for example this command:
```bash
wget https://huggingface.co/LLukas22/gpt4all-lora-quantized-ggjt/resolve/main/ggjt-model.bin
```

```php
$template = "You are a programmer, write PHP class that will add two numbers and print the result. Stop at class end.";
$context = Context::createWithParameter(new ModelParameters(__DIR__ .'/models/ggjt-model.bin'));
$llama = new LLamaCPP($context);
echo "Prompt: \033[0;32m".$template."\033[0m".PHP_EOL;

foreach ($llama->generate($template, new GenerationParameters(predictLength: 200)) as $token) {
    echo $token;
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

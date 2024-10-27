=== Text Prompter - Unlimited chatgpt text prompts for openai tasks ===
Contributors: flippercode
Donate link: 
Tags: chatgpt, openai, gpt,ai,content
Requires at least: 4.0
Tested up to: 6.5.3
Stable tag: 1.0.7
Requires PHP: 5.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Effortlessly create, manage, and use chatgpt text prompts for openai tasks and use shortcode [text_prompter].

== Description ==

Text Prompter is a powerful openai plugin that allows you to use chatgpt text prompts effortlessly with gutenberg.

https://www.youtube.com/watch?v=OkMs-SD3z7g


The plugin allows them to create chatgpt text prompts and test them to see which one perform best. By having the ability to create their own prompts, content creators can tailor the generated content to their specific needs and preferences with help of openai api.

# Features List

* Create new openai prompt
* Manage existing text prompts
* Import bulk text prompts
* Export bulk text prompts
* Test prompts
* Use chatgpt prompts in Gutenberg
* Add a new block in Gutenberg using openai
* Upate an existing block in Gutenberg using openai
* Use [text_prompter] shortcode to generate dynamic contents.

One of the most notable features of this plugin is its ready-to-use text prompts, which are pre-written and optimized for readability. 

* Write a paragraph: Write a paragraph on the given topic.
* Add content: Add 2-3 more sentences to the given content without changing anything else.
* Generate ideas: Generate 3-5 ideas in bullet points based on the given topic.
* Write a blog post: Write a blog post with 3-5 headings and 3-5 paragraphs on the given topic.
* Generate a TL;DR: Summarize the given content in 1-2 sentences.
* Write a summary: Write a summary of the given content in 1-2 paragraphs.
* Paraphrase: Paraphrase the given content without changing the meaning.
* Generate a tagline: Generate a catchy tagline for the given content.
* Explain to a 2-year-old: Explain the given content in simple language suitable for a 2-year-old child.
* Write a quote: Generate a relevant quote based on the given content.
* Correct grammar: Correct any grammatical errors in the given content.
* Generate a topic outline for blog: Create a topic outline for a blog post on the given topic.
* Change tone: Change the tone of the given content to be more formal.
* Make it conversational: Rewrite the given content to make it more conversational and friendly.
* Adjust voice: Adjust the voice of the given content to be more authoritative and confident.
* Proofread: Proofread the given content and correct any spelling or grammatical errors.
* Fix spelling errors: Fix all spelling errors in the given content without making any other changes.
* Add two lines: Add two more lines to the given content without making any other changes.
* Add one line: Add one more line to the given content without making any other changes.
* Add a heading and a paragraph: Add one heading and one paragraph to the given content without making any other changes.
* Generate a Call-to-Action: Create a compelling call-to-action for this content.
* Add Bullet Points: Add a list of bullet points to summarize this content.
* Expand on an Idea: Take one of the ideas in this content and expand on it.
* Improve Readability: Rewrite this content to improve its readability and flow.
* Add Examples: Add some examples to illustrate the points made in this content.
* Add a Subheading: Add a subheading to break up this content and make it easier to read.
* Create a Product Description: Write a description for this product that will entice users to buy it.

# Shortcode [text_prompter]

We have introduced a new shortcode [text_prompter] that allows you to easily generate AI-generated content based on a specified prompt.

Inside the post editor, add the shortcode with the following format:

`[text_prompter model="MODEL_NAME" temperature="TEMPERATURE_VALUE" max_tokens="MAX_TOKENS_VALUE" transist="TRANSIST_VALUE" attributes="ATTRIBUTE_VALUE"]PROMPT_CONTENT[/text_prompter]`

Where:

* **MODEL_NAME** (optional, default: text-davinci-002) is the name of the OpenAI language model to use for generating the prompt response.
* **TEMPERATURE_VALUE** (optional, default: 0.5) is the temperature value to use for generating the response. The higher the value, the more unpredictable the response.
* **MAX_TOKENS_VALUE** (optional, default: 50) is the maximum number of tokens to generate in the response.
* **TRANSIST_VALUE** (optional, default: 86400) is the time in seconds for which the generated response will be cached. This is to prevent unnecessary API requests to OpenAI.
* **ATTRIBUTE_VALUE** (optional) is a string containing any additional attributes you want to add to the generated HTML output.
* **PROMPT_CONTENT** (required) is the text prompt to use for generating the response.

Note: You can use any valid HTML attributes in ATTRIBUTE_VALUE. For example, to add a class to the output, you can use attributes="class='my-class'".

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the <strong>text-prompter</strong> directory to the <strong>/wp-content/plugins/</strong> folder

2. Once the plugin is uploaded, log into WordPress and go to <strong>Plugins</strong>

3. Find the <strong>text-prompter</strong> plugin and click <strong>Activate</strong>

# How to use

1. Go to the <strong>Settings</strong> page of the plugin and insert your OpenAI API key. See full instructions on How to create OpenAI API key

2. Create your own text prompt or import pre-built text prompts using the 'Add Prompt' or 'Import Prompts' page.

3. Test prompts using the 'Testing' page.

Now you are ready to use text prompts in the Gutenberg editor.

== Frequently Asked Questions ==

= How do I get an OpenAI API key? =

To get an OpenAI API key, you will need to sign up for an account on the OpenAI website. Once you have an account, you can create an API key by visiting the API key management page on the website.

= What can I do with an OpenAI API key? =

An OpenAI API key can be used to access various services and models offered by OpenAI. This includes using models such as GPT-3 for natural language processing and generation, and other AI-based services.

= How much does it cost to use the OpenAI API? =

The OpenAI API is currently in beta and is offered on a pay-per-use basis. The cost of using the API will depend on the usage of the service and the specific model you are using. You can find more information on pricing on the OpenAI website.

= How secure is my OpenAI API key? =

OpenAI takes the security of your API key very seriously. The company recommends that you keep your API key secure and avoid sharing it with others. Additionally, the OpenAI website provides guidance on how to manage and secure your API key.

== Changelog ==

= 1.0.7 =
* Fix : Max Token error is resolved.

= 1.0.3 =
* New : Add new block.
* New : Update existing block.
* New : Add heading type block.
* New : Add paragraph type block.

= 1.0.2 =
* Fix : Plugin details updated.

= 1.0.1 =
* Fix : Code Improvements

= 1.0.0 =
* New : Add new text prompt
* New : Manage text prompts for editing or removing.
* New : Import text prompts for bulk editing.
* New : Export text prompts to move to another installation.
* New : OpenAI API Setting page.


== Screenshots ==

1. Basic text prompt details can be added with the user-friendly interface.
2. Advanced text prompt settings can be accessed for more control over the content.
3. The plugin seamlessly integrates with the Gutenberg editor for enhanced content creation.
4. The plugin allows for bulk import of text prompts for convenience.
5. The management page allows for easy editing of text prompts.
6. A dedicated OpenAI settings page allows for fine-tuning of the plugin's settings.
7. The prompts testing page allows users to test and evaluate different prompts.
8. How to Use Shortcode.

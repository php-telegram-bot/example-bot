This Folder contains some examples of how to use the Telegram Bot API with the Telegram PHP Bot library


**:exclamation: DO NOT USE THE COMMANDS FOLDER**

As the title says, do not include the Commands folder into your config to use it as it is.

Why? This folder contains some files multiple times, which is an issue for the bot software, since every file can only exist once!

For example: GenericmessageCommand.php
This file exists in the following folder:
- Conversation
- Group
- Message
- Payments

Having any Command file more than once will cause conflicts between those file, causing only one file to be executed and the others ignored.

Please copy each file and/or folder that you need into the CustomCommans folder.

If you want to create your own Command file, please do it so in the CustomCommands folder as well.

If you need, for example, the GenericmessageCommand.php from the Conversation and Group folder, you will need to compare and merge those files. 


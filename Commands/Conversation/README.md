# Conversation

Conversations can be used to create dialogues with users, to collect information in a "conversational" style.

Look at the [`SurveyCommand`](SurveyCommand.php) to see how a conversation can be made.

For conversations to work, you must add the code provided in [`GenericmessageCommand.php`](GenericmessageCommand.php) at the beginning of your custom `GenericmessageCommand::execute()` method.

The [`CancelCommand`](CancelCommand.php) allows users to cancel any active conversation.

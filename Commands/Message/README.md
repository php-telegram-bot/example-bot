# Message

You bot can handle all types of messages.

## Private and Group chats

Messages in private and group chats get handled by [`GenericmessageCommand.php`](GenericmessageCommand.php).
When a message gets edited, it gets handled by [`EditedmessageCommand.php`](EditedmessageCommand.php)

(Have a look at [`EditmessageCommand.php`](EditmessageCommand.php) for an example of how to edit messages via your bot)

## Channels

For channels, the messages (or posts) get handled by [`ChannelpostCommand.php`](ChannelpostCommand.php).
When a channel post gets edited, it gets handled by [`EditedchannelpostCommand.php`](EditedchannelpostCommand.php)

# Group or Channel

Requests specific to groups and channels all get handled in [`GenericmessageCommand.php`](GenericmessageCommand.php).

The two extra commands [`NewchatmembersCommand`](NewchatmembersCommand.php) and [`LeftchatmemberCommand`](LeftchatmemberCommand.php) are simply files that can be called as commands from within a command, not by a user.
Have a look at [`GenericmessageCommand.php`](GenericmessageCommand.php) to understand what you can do.

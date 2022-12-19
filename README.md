# ServerMessages
A simple plugin that will allow you to customize server messages.
## How to use
1. Put the plugin inside the `plugins` folder.
2. Restart the server.
3. Edit the config file.
4. Restart the server once again.
## Config
```yaml
join:
  enable: true
  message: "&a+ {player}"

quit:
  enable: true
  message: "&c- {player}"

death:
  enable: true
  message: "&cThe player &e{player} &cdied."

kill:
  enable: true
  message: "&cThe player &e{player} &cwas has been killed by the player &c{killer}"
```
## Credits
The plugin icon made by [Jonny Studio](https://www.flaticon.com/authors/jonny-studio) from www.flaticon.com
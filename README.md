# FieldFlags
An airsoft field remote flag (wireless mesh) system with internet feedback and control for players and operators.

This was a project I have abandoned, but got fairly well into things before I ran out of time and energy to tweak it. Unfortunately, there are multiple constraints such as very slow internet connectivity and multiple wireless interference issues as well as the code starting to get very complex. 

Ideally, this is a esp8266 main controller, with pro mini nodes and repeaters that all use the NRF24L01 radios to form a healing mesh network on the 2.4 GHz band (not wifi, but the same frequency).  The main controller is centrally located on the field and uses a wireless hotspot to report data and take commands from a web server running php, and mysql for a db. 

A web page that the field operators access allows statistics for the game play, field light control, and another web page can offer the players read only status updates.

Different game modes are baked into the controller but the most useful one - aside from general get read, game on, game end feedback to the players, was capture the flag mode, as the system would track the amount of time the player teams owned each flag.

This system was used in a few large events and did work in the basic mode but started to fail constantly, due to unknown reasons. I suspect wireless band interference (perhaps due to lots of drones in use?), but as the nodes are all pro mini theres not a lot of ways i could easily (or cost effectively) really do debug logging.

We also tried to add in a node that could play prerecorded mp3 sounds or songs based on a trigger from the admin, however, the current demands of the drok mp3 player made that flag consume a lot of battery.. and there was a limit to how much we could spend on solar charging and battery systems.

So I've released the code and work i did up to this point to the community, in hopes that it might help someone else out. Note than in a large part the framework for nodes and reporting and traffic management was handled by the mysensors 2.1 framework. I generally modded and enhanced the code for my uses and did not use a typical setup with a windows or ras-pi or other "controller" as you would in a home automation scenario.. in this system the wifi gateway also did a lot of the code and game flow work, in conjunction with getting and sending data to the mysqldb via php.

Note that i really dont think this is a bad setup - you may have a much simpler scenario where you just need to remotely alert people across a large area about status.. this setup might work realy well for that - but then the basic mysensors network might also do that without having to add in the mods that i used, either.

Enjoy! I wont be supporting this but if i notice a question i'll try to answer as much as i can remember..

## Introduction

I found this test really interesting and enjoyable, I think it allows a developer to showcase many of their skills, but I also think it's quite a big task to be made in four hours (that's without considering the bonus points) and still deliver quality code. In my case, it took me about nine hours to do this and I can still think of many improvements that could be done here, which weren't made not because of lack of knowledge but because of time issues. I tried to show at least a bite of as many things I could but I know there are things that I applied in a single part of the application that probably should have been applied in many other places. Overall, I'm satisfied with the final codebase and open to talk about my decisions and mistakes.

## Explaining decisions

- There might be other ways to store Cells instead of using a model for them (like a json structure on the game) that could allow memory saving, I'm aware with multiple users and big boards the cells table could grow quickly. It would have been posible to use something like jessenger/models to then build objects using that json structure to make it easier to use. But with this approach I was able to use many of the framework features and I think if a performance/memory issue is detected in the future it would be possible to schedule a Job to do a cleanup over ended games (or storing those ended games in a json field and create a different way to query those).
- Many edge cases aren't being taken care of (performing actions over ended games).
- Some things like a few accessors were implemented but not used. It was only because I tried to include as many features as I could, but they might not be necessary.
- Regarding authentication, there was no explicit indication about it and the "Ability to support multiple users/accounts" bullet was at the bottom of priority. I used laravel/passport since it's a well-known and widely-used library, and my approach was thinking this API would be consumed by many external applications (e.g. an app, a browser version) so I used the Oauth2 delegation protocol to allow them to authenticate without permanently exposing their user and password on every request. It would be those application's responsibility to internally restrict their users from modifying other user's games within their platforms, this only ensures that no application can modify another application's games.
- Liked using SQL to find adjacent cells but it could have also be done with php and maybe some math calculation regarding matrix positions. There's probably a good algorithm out there.
- Considering it took me almost twice the suggested time I was forced to discard some ideas, so no logging at all is being than and exceptions are not enough and could be handled in a better way. Factories have no states. No mutators were implemented. 


## Needed improvements

- All the entity IDs are exposed! I wanted to switch to using an external-id through a uuid field in the models to avoid using actual database IDs on routes and responses. My intention was to generate those IDs throug an Observer in the creating method, but I couldn't spend more time working on this.
- Some algorithms like the board construction or the adjacent cells discovery can probably be improved. I tried not to google that to allow you to see how I though about it but I'm sure there must be more performant ways to accomplish that.
- Extra validation is needed on the CreateGameRequest regarding the amount of mines not beeing greater than the board. Also, more requests could have been created.
- I tried to add at least a few tests to show some of my knowledge regarding TDD and PHPUnit but the truth is there's really little coverage. No Feature testing, no mocking, many critical functionality doesn't have a test. But once again, I couldn't spend more time on this.
- No control over performing actions like uncovering/flagging cells on ended games.
- I stopped type hinting and commenting many functions when I realized I wasn't going to make it on time.
- Poor documentation.
- No UI.

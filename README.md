# Camect Snapshot Proxy
This PHP script will take camera Id's from your camect system and connect to
your local device and obtain a camera snapshot (jpg). To work, your device
needs network connectivity to your local camect server via https. This is based on [camect-py](https://github.com/camect/camect-py)
python library designed to interact with your
[Camect](https://www.camect.com). The intention of this for me is so that I can
include snapshots of each camera on my
[Hubitat](https://github.com/bdwilson/hubitat) Dashboard.

# Requirements
You'll need a system that has PHP on it. Tested with PHP 7. 

# Installation 
1. You'll need to then navigate to [https://local.home.camect.com](https://local.home.camect.com) and accept the Terms of Service. You'll end up on your local server and the name will be **xxxxxx**.l.home.camect.com. This beginning part is considered your **Camect Code**. 
2. You'll then need to determine your username and password - the username in the default case is **admin** and the password is the first part of your email address that you used to register your camect device - for instance, bob@gmail.com would give you the password "bob".
3. Fill out the above items in camect.php and make sure camect.php is accessible.  There is an optional auth_code variable that will make it so that each call to camect.php must also include a **&auth=** directive including this value. This is just for extra security, but not required as CamId's are GUID's and not predictable. 
4. Navigate to https://home.camect.com, click the pop-out icon for a camera and obtain the CamId.
5. Next call your script like this, replacing CAMID with your id from above <code>http://myserver/camect.php?snapshot=CAMID</code>
6. Optional arguments: <code> &width=xxx &height=xxx </code>

Bugs/Contact Info
-----------------
Bug me on Twitter at [@brianwilson](http://twitter.com/brianwilson) or email me [here](http://cronological.com/comment.php?ref=bubba).

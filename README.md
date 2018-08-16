

# Trello Workflow for Alfred App v.1.6.1 

### Create cards in Trello using Alfred App [https://www.alfredapp.com/](https://www.alfredapp.com/)

### [Download Trello WorkFlow 1.6.1](https://github.com/MikoMagni/Trello-Workflow-for-Alfred/releases/tag/1.6.1)

## Install

1. Double click on the "**Trello Worklfow for Alfred v.1.6.1**" workflow that you have just downloaded.  
More info: [https://www.alfredapp.com/help/workflows/](https://www.alfredapp.com/help/workflows/)  

    Note: if you have version 1.5 installed, remove it before installing the new version.
   


## Setup

1. **Generate your Trello Developer API Key**<br> 
Use the keyword "**get trello key**" to generate your Trello Developer API Key.<br>
More information: [https://developers.trello.com/docs/api-introduction](https://developers.trello.com/docs/api-introduction).  

    **Note:** Make sure to be logged in Trello in your default browser before generating your API Key.  
![](https://user-images.githubusercontent.com/2527231/39163817-68b8092a-47bb-11e8-939e-62cdfff3ed3b.png)

2. Copy your **API Key**

3. **Authorize Trello Workflow**  
Use the keyword "**get trello token**" plus your "**API Key**" to authorize the Trello Workflow to use your Trello account  

    Example: **get trello token 00000000000000000000**  
    More information: [https://developers.trello.com/docs/api-introduction](https://developers.trello.com/docs/api-introduction)<br>
![](https://user-images.githubusercontent.com/2527231/39280550-2a5bdb0e-493f-11e8-96de-81a64ce5cf17.png)

4. **Allow** Trello Workflow to use your account  

     ![](https://user-images.githubusercontent.com/2527231/39164571-364a56b0-47bf-11e8-92f3-3c2a08fd04e9.png)

5. Copy your **Token**

6. **Your Trello board id**  
Choose the Trello board that you wish to use with Trello Workflow and copy the **board id**  
You can get the board id by simply going to your board and add .json at the end of the URL.  

     **Example:** Go to the Trello developmemt Roadmap Board [https://trello.com/b/nC8QJJoZ/trello-development-roadmap](https://trello.com/b/nC8QJJoZ/trello-development-roadmap). To view the board id add .json at the end of the URL [https://trello.com/b/nC8QJJoZ/trello-development-roadmap.json](https://trello.com/b/nC8QJJoZ/trello-development-roadmap.json). You should now see the full JSON
    >{"id":"**4d5ea62fd76aa1136000000c**","name":"Trello Development Roadmap","desc":"","descData"

    The board id in the example is: **4d5ea62fd76aa1136000000c**  

7. Open the Trello Workflow for Alfred in Alfred app. Use the Keyword **Alfred** to 
Show Alfred Preferences. Navigate to Workflows and select Trello Workflow for Alfred v1.6 from the side column. 

	![](https://user-images.githubusercontent.com/2527231/39165421-86508e96-47c3-11e8-8f90-f06bc0a6727f.png)  

8. Double click on the **/bin/bash** script and enter your **API Key**, **Your Token** and your **board id** here:  

	> key='**{YourAPIKey}**'  
	> token='**{YourPersonalToken}**'  
	> boardid='**{YourBoardId}**'  
	
	Make sure that each preference in the bash file is within single quotes:
	
	> key='00000000000'   
	> token='0000000000000000000000000000000'  
	> boardid='0000000' 
	
	
	Click **Save**  

	![](https://user-images.githubusercontent.com/2527231/39165568-388c8448-47c4-11e8-9864-fc32d2eaf9ad.png)



## Usage

1. General usage **trello** **{field}** separate fields using **;**  

      You can choose to have spaces or not between fields. For example **{field1}; {field2}** and **{field1};{field2}** will work.  
    
      Available fields: **{Card Title}; {Card Description}; {Labels}; {Due Date}; {List Name}; {Card Position}** 
![](https://user-images.githubusercontent.com/2527231/39163922-f2d0f252-47bb-11e8-9bba-4b537528bd27.png)


 ## Basic Usage  
 
 
 **Card Title**  
 
 ```
 trello make dinner reservation
 ```  
 
 will create a card on your board on the first list with the title "make dinner reservation"

 ![sdsd](https://user-images.githubusercontent.com/2527231/39225051-73d684a2-4889-11e8-9273-bfb21a1abc7d.png)  
 
  ![sdsd](https://user-images.githubusercontent.com/2527231/39226873-50f9dbb8-4894-11e8-8864-0ce57a8a385d.png)
 
 **Card Description**  
 
 ```
 trello make dinner reservation; table for 10 people at around 7:30pm
 ```
 
 will create a card on your board on the first list with the title "make dinner reservation" and description "table for 10 people at around 7:30pm"
 
 ![](https://user-images.githubusercontent.com/2527231/39225192-166199c8-488a-11e8-8f38-015befdc412c.png)  
 
 ![](https://user-images.githubusercontent.com/2527231/39226879-638a9a1a-4894-11e8-8449-2ae9f97af35e.png)  
 
 **Labels**  
 
 ```
 trello make dinner reservation; table for 10 people at around 7:30pm; blue
 ```
 
 will create a card on your board on the first list with the title "make dinner reservation" and description "table for 10 people at around 7:30pm" with a "blue" label  
 
 **Available Labels**  
 - **all** (will add green, yellow, orange, red, purple and blue)
 - **green**
 - **yellow**
 - **orange**
 - **red**
 - **purple**
 - **blue**

 You can add more than one label by comma separating them.
 
 ```
 trello make dinner reservation; table for 10 people at around 7:30pm; blue,red,yellow
 ```
 
 Please note: Make sure not to have spaces between comma separated labels.  
 Custom labels are not supported. If you find a way let me know :)  
 
 ![](https://user-images.githubusercontent.com/2527231/39225416-6a59e25a-488b-11e8-8d0e-e7b6c2f3fe81.png)  
 
 ![](https://user-images.githubusercontent.com/2527231/39226897-84c4c976-4894-11e8-84d2-c11daa3e37b4.png)  
 
 ![](https://user-images.githubusercontent.com/2527231/39226930-b52e97ae-4894-11e8-92e3-37052eac9794.png)


**Due Date**  
 
```
trello make dinner reservation; table for 10 people at around 7:30pm; blue; 04/26/2018
```

will create a card on your board on the first list with the title "make dinner reservation" and description "table for 10 people at around 7:30pm" with a "blue" label.
The due date will be set as 04/26/2018  

![](https://user-images.githubusercontent.com/2527231/39225889-2a305bf2-488e-11e8-82e2-4da85e9db1ab.png)  

![](https://user-images.githubusercontent.com/2527231/39226946-e22d4368-4894-11e8-8071-f7b78742d768.png)

**List Name**  
 
```
trello make dinner reservation; table for 10 people at around 7:30pm; blue; 04/26/2018; Today
```

will create a card on your board on the list **Today** with the title "make dinner reservation" and description "table for 10 people at around 7:30pm" with a "blue" label.
The due date will be set as 04/26/2018.

Please note: **List name are case sensitive**  today will not work if your list is named Today.

The example will only work if you have a list named Today, otherwise the card will be created on your first list.  

![](https://user-images.githubusercontent.com/2527231/39226075-44f065e4-488f-11e8-900c-b2474b7d06e4.png)  

![](https://user-images.githubusercontent.com/2527231/39226084-57dd980c-488f-11e8-93a5-ebdf397fdffa.png)


**Card Position**  
 
```
trello make dinner reservation; table for 10 people at around 7:30pm; blue; 04/26/2018; Today; top
```

will create a card on your board on the list **Today** with the title "make dinner reservation" and description "table for 10 people at around 7:30pm" with a "blue" label.
The due date will be set as 04/26/2018.  

Note: If you don't specify a card position, your new card will automatically be placed at the end of the list. 

**Available options (case sensitive)**  
- **top**  
- **bottom**

![](https://user-images.githubusercontent.com/2527231/39225889-2a305bf2-488e-11e8-82e2-4da85e9db1ab.png)  

**bottom**  

![](https://user-images.githubusercontent.com/2527231/39226984-280ab73a-4895-11e8-9892-541213eec1e4.png)  

**top**  

![](https://user-images.githubusercontent.com/2527231/39226985-283cb2bc-4895-11e8-9939-1a4bcdb8525d.png)


## Advanced Usage  

You can skip any of the available fields by simply adding **;**  

**{Card Title}; {Card Description}; {Labels}; {Due Date}; {List Name}; {Card Position}**

For example if I wanted to post a card with Title, Label and a Due date i would use this syntax

**{Card Title}; ; {Labels}; {Due Date}**

```
trello Clean my car; ; red; 04/29/2018
```

![](https://user-images.githubusercontent.com/2527231/39227249-c4188d86-4896-11e8-9697-eff35768b368.png)  

![](https://user-images.githubusercontent.com/2527231/39227247-bef258aa-4896-11e8-8f2b-52e8e7d73504.png)  

Or a card with title only but on a different list  

**{Card Title}; ; ; ; {List Name}**  

```
trello Clean my car; ; ; ; Upcoming
```  

![](https://user-images.githubusercontent.com/2527231/39227342-4c118abc-4897-11e8-9486-4f008fd8fbd5.png)  

![](https://user-images.githubusercontent.com/2527231/39227333-3f9a37a2-4897-11e8-81ae-7089f22153a6.png)  


## Environment Variables by @gamell

Given that some might want always to create the cards on the same list, or with the same label, or same due date, or same position _by default_, I added the ability to set those defaults via the environment variables `trello.list_name`, `trello.label`, `trello.due` and `trello.position`.

One can conveniently add or edit those environment variables without programming knowledge through the Alfred Workflow editor, clicking on the `[x]` button on the top right (see screenshot below). 

*Note:* If you don't set the variable, the workflow will behave as it did before.

![](https://user-images.githubusercontent.com/2460215/44072791-96f57f66-9f45-11e8-9dbc-399744c5c34c.png)

![](https://user-images.githubusercontent.com/2460215/44072799-9d4107b4-9f45-11e8-9444-4d71f7f8135f.png)


## FAQ  

Coming soon

 
## License

[MIT](https://github.com/MikoMagni/Trello-Workflow-for-Alfred/blob/master/MIT%20License) © Miko Magni

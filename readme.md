# Love Island Stats

## Overview
This is a simple fit-for-purpose tool for live data collection while watching the hit reality show Love Island.<br>
Ever since becoming a Love Island fan a few years ago, I've wanted to do a data project similar to what @Bachelordata does for the Bachelor Franchise.
Ultimately my attempts have failed because collecting data on the show ruins my viewing experience, which I cherish so much.
This time around, I decided to get some web development skills out of it, while making data collection more pleasant.<br>

**A note on Gender in this project:** You'll notice gender is represented as binary in this application (boy, girl). This merely represents the structure of the show Love Island, and not my personal ideology about gender. I hope that soon popular media will catch up with the fact that gender is a construct. There are certainly non-binary folks that would love to be on Love Island too... *eh hem, producers...*

## Technology Used
- [Bootstrap 4 Starter WordPress Theme](https://afterimagedesigns.com/wordpress-bootstrap-starter-theme/)
- Page templates for data collection were developed with PHP and Bootstrap. They can be found here:
    - wp-content > themes > \_wp-bootstrap-starter > love-island-forms     
- The page templates execute SQL commands to read and write from the mySQL database.
- Hosted on a Bluehost.

## Usage
### Entering Actions 
This page is designed to capture single actions that occur between two contestants and sometimes just one contestant <br>
To illustrate, I'll walk through the workflow for watching Love Island Australia, Season 3, Episode 4, supposing Jess pulled Aaron for a chat (no spoilers this is hypothetical....or is it??)
1. Navigate to https://loveislandstats.com/action-entry/
2. In the lower left form box, enter in the appropriate Series: Australia, Season: 3, Episode: 4.
    - This will be automatically populated with the most recent entry, so you don't have to re-fill it.
4. Press the "Jess" button in the left column. The left column is the acting islander. For mutual actions (a kiss for example), which islander you click in which column doesn't matter. 
5. Press the "Chat Pull" button from the center "Action" column.
6. Press "Aaron" button in the right column. The right column is for the islander that the action is being performed on. See point 3 above for mutual actions.
7. Enter any additional notes in the "Notes" field
8. Press "Submit Action"
9. If successful, you should see "✅ Jess + Chat Pull + Aaron ✅" At the top of the page.

![Action Entry Demo](https://github.com/alexrkoch/love-island-stats/blob/main/media/love-island-stats-action-demo.gif)

### Adding or Removing Islanders
In Love Island, contestants (Islanders) can be dumped from the island, and new Islanders can join. To manage this with our data collection app, we do the following:
1. Navigate to https://loveislandstats.com/edit-islanders/
2. Enter the name of the islander
3. Click the button for the islander's gender, and whether they are Entering or Exiting
4. Click "Edit Islanders" 
5. If successful, you should see "✅ Alex + Enter ✅" at the top of the page (With whatever criteria you entered).

![Edit Islanders Demo](https://github.com/alexrkoch/love-island-stats/blob/main/media/love-island-stats-islander-demo.gif)

### Where the Data Goes on the Backend
The database is a mySQL database, the standard for WordPress sites. I've added tables for the islanders, as well as actions. See the screenshots below to see how the above examples show up in the database.

Here is the full wp Database:<br>
<img src="https://github.com/alexrkoch/love-island-stats/blob/main/media/love-island-stats-database-structure.png" alt="Database Structure" width="300"/>

This is what the Actions table looks like, notice the last entry from our example above: <br>
<img src="https://github.com/alexrkoch/love-island-stats/blob/main/media/love-island-stats-actions-table.png" alt="Actions Table" width="800"/>

This is what the Islanders table looks like (boys), noticed the last entry from our example above:<br>
<img src="https://github.com/alexrkoch/love-island-stats/blob/main/media/love-island-stats-islanders-table.png" alt="Islanders Table" width="400"/>


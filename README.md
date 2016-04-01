# Neoslive.Templateinheritance

Create fallback templates to defined sites/packages.

 In an advanced Neos project, you will create lots of sites and node types. However, many node types are shared between packages and sites. If you think about sharing corresponding templates, you should use the following configuration.
 
 For instance, the node type "Chapter" is defined in Neos site "My.Globals" and it is used in Neos sites "My.FooTest" and  "My.BaaPage" then you can set up your templates this way:

**create:**
resource://My.Globals/Private/Templates/NodeTypes/Chapter.html
  and
resource://My.FooTest/Private/Templates/NodeTypes/Chapter.html  

**but don't create:**
resource://My.BaaPage/Private/Templates/NodeTypes/Chapter.html

**add to global Settings.yaml**

    Neoslive:
      Templateinheritance:
        Packages:
          'My.Globals':
            'My.Globals': TRUE
          'My.BaaPage':
            'My.Globals': TRUE

In the above example, the follwing templates are used for each site:

My.Globals:
*resource://My.**Globals**/Private/Templates/NodeTypes/Chapter.html*

My.BaaPage:
*resource://My.**Globals**/Private/Templates/NodeTypes/Chapter.html*
 
 My.FooTest:
*resource://My.**FooTest**/Private/Templates/NodeTypes/Chapter.html*




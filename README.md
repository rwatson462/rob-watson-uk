# rob-watson-uk
The code for my actual website at http://rob-watson.uk
(yes, `http` because I'm too cheap to pay for SSL)

## Key features:

1. Straight forward
> With a single point of entry, we just query a single database table to find
  a template file name and any additional data needed to display the template.
  Then we load it.
2. Fast
> With very little processing required, pages can be loaded really quickly.
  This needs to carry through to the templates themselves, which will also
  be as light-weight as posible.
3. Simple
> No autoloaders, no class heirarchy, just plain PHP code with minimal extra
  loaded in.
# pagination-bundle for Symfony framework.

### INSTALLATION:

1) Add pagination repo in composer.json
```javascript
"repositories" : [{
    "type" : "vcs",
    "url" : "https://github.com/moreoverid/pagination-bundle.git"
}],
```

2) Install PaginationBundle via composer:
```bash
php composer require moreoverid/paginationbundle "dev-master"
```

3) Register PaginationBundle in app/AppKernel.php:
```php
$bundles = [
    ...
    new Moreoverid\PaginationBundle\PaginationBundle(),
];
```

4) Add new service in app/config/services.yml:
```yml
services:
    app.pagination:
        class: Moreoverid\PaginationBundle\Model\Pagination
```

### USAGE:

1) Save pagination service in variable:
```php
$pagination = $this->get('app.pagination');
```

2) Get count of all your objects (example for Doctrine):
```php
$count = count($em->getRepository('DemoBundle:SomeEntity')->findBy(array());

$paginator = $pagination::paginate($page, $count, 12); // 12 is per page limit

$collection = $this->getDoctrine()->getRepository('DemoBundle:SomeEntity')
    ->findBy(array(), array(), $paginator['count_per_page'], $paginator['offset']);
```

3) Add page variable to your routing.yml and controller:
```yml
# in routing.yml
some_route_name:
    path:     /some-path/{page}
    defaults: { _controller: DemoBundle:Default:yourMethod, page: 1 }
```
```php
// in controller
public function yourMethodAction(Request $request, $page = 1)
{
     return $this->render('DemoBundle:Default:some_template.html.twig',
            array(
                'page' => $page,
                'paginator' => $paginator,
                'collection' => $collection,
            ));
}
```
4) Add path for twig template in app/config/config.yml:
```yml
# Twig Configuration
twig:
    paths:
        '%kernel.root_dir%/../vendor/moreoverid/paginationbundle/Moreoverid/PaginationBundle/Resources/views': pagination_bundle
```

5) Inside your view template you can use PaginationBundle template (add paginator variable passed to Twig from controller as first argument and add your custom CSS class as second argument):
```twig
{# pagination view template #}
{{ include('@pagination_bundle/Frames/index.html.twig', {'pagination': paginator, 'class': 'pagination'}) }}
```

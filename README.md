# IIIF Bundle

This is a Symfony 3.x Bundle to get an IIIF Representation out of arbitrary data structures.

## Example configuration

```
subugoe_iiif:
  image:
    http:
        scheme:           "https"
        host:             "images.example.com"
    tile_width:           512
    tile_width:           512
    thumbnail_size:       "96,"
    zoom_levels:          [1, 2, 4, 8, 16]
    adapters:
      source:
        class:            Ipf\Flysystem\Httpget\HttpGetAdapter
        configuration:
          base_uri:       "http://gdz.sub.uni-goettingen.de/tiff/"
  presentation:
    logo:                 "http://gdz.sub.uni-goettingen.de/fileadmin/gdz/layout/head_logo.jpg"
    service_id:           "http://gdzdev.sub.uni-goettingen.de/"
    http:
        scheme:           "https"
        host:             "images.example.com"
```

## Format translator

It is also necessary to define a so-called translator-service with the name ```subugoe_iiif.translator```, i.e.:
```
  subugoe_iiif.translator:
    class:      Subugoe\IIIFBundle\Translator\SubugoeTranslator
    arguments:  ["@subugoe_find.search_service"]
```

The translator has to implement the ```Subugoe\IIIFBundle\Translator\TranslatorInterface```. An example translator is bundled,
see the SubugoeTranslator class.

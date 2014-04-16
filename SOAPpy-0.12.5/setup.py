#!/usr/bin/env python
#
# $Id: setup.py,v 1.11 2005/02/15 16:32:22 warnes Exp $

CVS=0

from setuptools import setup, find_packages
import os

def read(*rnames):
    return "\n"+ open(
        os.path.join('.', *rnames)
    ).read()



def load_version():
    """
    Load the version number by executing the version file in a variable. This
    way avoids executing the __init__.py file which load nearly everything in
    the project, including fpconst which is not yet installed when this script
    is executed.

    Source: https://github.com/mitsuhiko/flask/blob/master/flask/config.py#L108
    """

    import imp
    from os import path

    filename = path.join(path.dirname(__file__), 'src', 'SOAPpy', 'version.py')
    d = imp.new_module('version')
    d.__file__ = filename

    try:
        execfile(filename, d.__dict__)
    except IOError, e:
        e.strerror = 'Unable to load the version number (%s)' % e.strerror
        raise

    return d.__version__


__version__ = load_version()


url="https://github.com/kiorky/SOAPpy.git"

long_description="SOAPpy provides tools for building SOAP clients and servers.  For more information see " + url\
    +'\n'+read('README.txt')\
    +'\n'+read('CHANGES.txt')\

if CVS:
    import time
    __version__ += "_CVS_"  + time.strftime('%Y_%m_%d')


setup(
    name="SOAPpy",
    version=__version__,
    description="SOAP Services for Python",
    maintainer="Gregory Warnes, kiorky",
    maintainer_email="Gregory.R.Warnes@Pfizer.com, kiorky@cryptelium.net",
    url = url,
    long_description=long_description,
    packages=find_packages('src'),
    package_dir = {'': 'src'},
    include_package_data=True,
    install_requires=[
        'fpconst',
        'wstools',
    ]
)


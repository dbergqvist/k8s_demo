import sys
import ConfigParser
from StringIO import StringIO

def main(config_filename, config_key):
    if config_filename == '-':
        config_file = sys.stdin
    else:
        config_file = open(config_filename)
    with config_file:
        parser = ConfigParser.SafeConfigParser()
        try:
            # Read the gcloud SDK config.
            # NOTE: (unset) causes parse errors so replace it with empty values.
            config_file = StringIO(config_file.read().replace("(unset)", "="))
            parser.readfp(config_file)
        except ConfigParser.Error:
            pass

    return parser.get(*config_key.split('/'))

if __name__ == '__main__':
    print(main(sys.argv[1], sys.argv[2]))

import { Text, TouchableOpacity, View } from 'react-native';

const Checkbox = ({ checked, onPress, label, linkTexts = [] }) => {
  return (
    <TouchableOpacity
      onPress={onPress}
      style={{
        flexDirection: 'row',
        alignItems: 'center',
        marginVertical: 16,
      }}
    >
      <View
        style={{
          width: 20,
          height: 20,
          borderRadius: 10,
          borderWidth: 2,
          borderColor: checked ? '#6B8BB8' : '#CCCCCC',
          backgroundColor: checked ? '#6B8BB8' : 'transparent',
          alignItems: 'center',
          justifyContent: 'center',
          marginRight: 10,
        }}
      >
        {checked && (
          <View
            style={{
              width: 8,
              height: 8,
              borderRadius: 4,
              backgroundColor: '#FFFFFF',
            }}
          />
        )}
      </View>
      <Text style={{ fontSize: 14, color: '#333333' }}>
        {label}
        {linkTexts.map((item, index) => (
          <Text key={index}>
            {' '}
            <Text style={{ color: '#6B8BB8', textDecorationLine: 'underline' }}>
              {item.text}
            </Text>
            {item.suffix || ''}
          </Text>
        ))}
      </Text>
    </TouchableOpacity>
  );
};

export default Checkbox;

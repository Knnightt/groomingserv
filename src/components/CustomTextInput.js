import { Text, View, TextInput } from 'react-native';

const CustomTextInput = ({
  label,
  placeholder,
  value,
  onChangeText,
  textStyle,
  containerStyle,
  inputStyle,
  secureTextEntry = false,
  rightElement,
}) => {
  return (
    <View style={[{ marginBottom: 20 }, containerStyle]}>
      {label && (
        <Text
          style={{
            fontSize: 14,
            fontWeight: '500',
            color: '#333333',
            marginBottom: 8,
          }}
        >
          {label}
        </Text>
      )}
      <View
        style={{
          flexDirection: 'row',
          alignItems: 'center',
          backgroundColor: '#F5F7FA',
          borderRadius: 25,
          paddingHorizontal: 20,
          paddingVertical: 4,
        }}
      >
        <TextInput
          placeholder={placeholder}
          placeholderTextColor="#999999"
          value={value}
          onChangeText={onChangeText}
          secureTextEntry={secureTextEntry}
          style={[
            {
              flex: 1,
              fontSize: 14,
              color: '#333333',
              paddingVertical: 14,
            },
            textStyle,
            inputStyle,
          ]}
        />
        {rightElement}
      </View>
    </View>
  );
};

export default CustomTextInput;

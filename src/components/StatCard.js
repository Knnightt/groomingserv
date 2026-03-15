import { Text, View } from 'react-native';

const StatCard = ({ 
  title, 
  value, 
  subtitle, 
  variant = 'default',
  style 
}) => {
  const isHighlighted = variant === 'highlighted';
  
  return (
    <View
      style={[
        {
          backgroundColor: isHighlighted ? '#6B8BB8' : '#F5F7FA',
          borderRadius: 12,
          padding: 20,
          flex: 1,
          marginHorizontal: 6,
        },
        style,
      ]}
    >
      <Text
        style={{
          fontSize: 12,
          fontWeight: '600',
          color: isHighlighted ? '#FFFFFF' : '#333333',
          textTransform: 'uppercase',
          letterSpacing: 0.5,
          marginBottom: 8,
        }}
      >
        {title}
      </Text>
      <Text
        style={{
          fontSize: 32,
          fontWeight: '300',
          color: isHighlighted ? '#FFFFFF' : '#6B8BB8',
          marginBottom: 4,
        }}
      >
        {value}
      </Text>
      {subtitle && (
        <Text
          style={{
            fontSize: 12,
            color: isHighlighted ? 'rgba(255,255,255,0.8)' : '#666666',
          }}
        >
          {subtitle}
        </Text>
      )}
    </View>
  );
};

export default StatCard;
